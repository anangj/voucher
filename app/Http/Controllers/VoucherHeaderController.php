<?php

namespace App\Http\Controllers;

use App\Mail\VoucherSent;
use App\Models\VoucherHeader;
use App\Models\VoucherDetail;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PackageVoucher;
use App\Models\Payment;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendVoucherEmailJob;
use App\Jobs\SendGmailApiEmail;
use Illuminate\Support\Carbon;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use App\Http\Controllers\EmailController;

class VoucherHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd('header');
        $breadcrumbItems = [
            [
                'name' => 'Voucher',
                'url' => route('vouchers.index'),
                'active' => false,
            ],
            [
                'name' => 'List',
                'url' => '#',
                'active' => true,
            ],
        ];

        // Filter Options
        // $data = VoucherDetail::with('voucherHeader.paketVoucher','voucherHeader.patient');
        $data =  VoucherHeader::with('paketVoucher','patient');
        // dd($data);

        $paket = PackageVoucher::select('name')->get();

        if ($request->filled('name')) {
            $data->whereHas('paketVoucher', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('status')) {
            $data->where('status', $request->input('status'));
        }
        // End Filter
        $vouchers = $data->get();
        // dd($vouchers);

        return view('voucher-headers.index', [
            'data' => $vouchers,
            'paket' => $paket,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Vouchers',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->input('selected_patients'));
        // $patients = collect();
        // checking from get data from API
        // if ($request->patients !== null) {
        //     // dd($request->patients);
        //     $patientsData = $request->patients;

        //     foreach ($patientsData as $patientData) {
        //         // dd($patientData);
        //         $data = Patient::create([
        //             'name' => $patientData['name_real'],
        //             'birthday' => \Carbon\Carbon::parse($patientData['tgl_lahir'])->format('Y-m-d'),
        //         ]);
        //         $patients->push($data);
        //     }
        //     return response()->json(['status' => 'success', 'patients' => $patients]);
        // }

        $selectedPatientId = session('patient_id');
        // dd($request->input('selected_patients'));

        // If selected_patients are passed as JSON, decode it (from previous form submission)
        $selectedPatients = $request->has('selected_patients') ? $request->input('selected_patients') : [];
        // dd($selectedPatients);

        

        // If have selected patients, fetch them from the database
        if (!empty($selectedPatients)) {
            // dd('as');
            $patients = Patient::where('id', $selectedPatients)->get();
            // dd('aa');
        }

        // If returning from update form with a specific patient ID, fetch that patient
        // if ($selectedPatientId) {
        //     dd('sd');
        //     $patients = Patient::where('id', $selectedPatientId)->get();
        //     dd('vv');
        // }
        // dd($patients);
        $breadcrumbItems = [
            [
                'name' => 'List Voucher',
                'url' => route('vouchers.index'),
                'active' => false,
            ],
            [
                'name' => 'Create',
                'url' => '#',
                'active' => true,
            ],
        ];

        $packageVoucher = PackageVoucher::all();
        $today = now()->format('Ymd');
        // setup for voucher number with running number . str_pad($runningNumber, 3, '0', STR_PAD_LEFT)
        $totalVoucher = VoucherHeader::where('purchase_date',now()->format('Y-m-d'))->count();
        $runningNumber = $totalVoucher + 1;
        
        $voucherNo = "V{$today}" . str_pad($runningNumber, 1, '0', STR_PAD_LEFT);
        // dd($voucherNo);

        return view('vouchers.create', [
            // 'patient' => $patients,
            'packageVoucher' => $packageVoucher,
            'breadcrumbItems' => $breadcrumbItems,
            'patients' => $patients,
            'voucherNo' => $voucherNo,
            'pageTitle' => 'Create Voucher',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // Validate the request inputs
        $request->validate([
            'paket_voucher_id' => 'required|exists:package_vouchers,id',
            'voucher_header_no' => 'nullable',
            'purchase_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:purchase_date',
            'patient_id' => 'required',
            'merged_data' => 'required|json', // Ensure merged data is in valid JSON format
        ]);

        // Decode merged_data
        $mergedData = json_decode($request->input('merged_data'), true);

        if (!$mergedData || !is_array($mergedData)) {
            return back()->withErrors(['merged_data' => 'Invalid format for merged data'])->withInput();
        }

        // Additional validation on the structure of merged_data
        foreach ($mergedData as $index => $patientData) {
            // Validate patient fields
            $validator = Validator::make($patientData, [
                'name' => 'required|string|max:255',
                'birthday' => 'required|date',
                'phone' => 'required|string|max:15',
                'email' => 'nullable|email|max:255',
                'family_members' => 'nullable|array',
            ]);

            // Stop processing if validation fails
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // If family members are present, validate each family member
            if (!empty($patientData['family_members'])) {
                foreach ($patientData['family_members'] as $familyIndex => $familyMember) {
                    $familyValidator = Validator::make($familyMember, [
                        'name' => 'required|string|max:255',
                        'birthday' => 'required|date',
                        'phone' => 'required|string|max:15',
                        'email' => 'nullable|email|max:255',
                    ]);

                    if ($familyValidator->fails()) {
                        return back()->withErrors($familyValidator)->withInput();
                    }
                }
            }
        }

        // Find the selected Paket Voucher
        $paketVoucher = PackageVoucher::find($request->paket_voucher_id);

        // Process the first patient from merged data
        $selectedPatients = $request->input('patient_id');
        $patientName = $mergedData[0]['name'];

        // Gather family members from the first patient in merged_data (assuming merged_data contains family members)
        $familyMembers = $mergedData[0]['family_members'] ?? [];

        // Get the latest voucher number in the system for running number
        $getVoucherHeader = VoucherHeader::where('paket_voucher_id', $paketVoucher->id)
            ->where('purchase_date', now()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();

        // dd($getVoucherHeader);

        if ($getVoucherHeader !== null) {
            $voucherDetails = VoucherDetail::where('voucher_header_id', $getVoucherHeader->id)
            ->orderBy('voucher_no', 'desc')
            ->first();

            $runningNumber = $voucherDetails ? intval(substr($voucherDetails->voucher_no, -3)) : 0;
        } else {
            $runningNumber = 0;
        }

        $qrVoucherHeader = [
            'voucher' => $request->input('voucher_header_no')
        ];

        $qrHeaderJson = json_encode($qrVoucherHeader);
        $qrCodeHeader = QrCode::format('svg')->size(200)->generate($qrHeaderJson);
        $qrCodeBase64Header = base64_encode($qrCodeHeader);

        // Create a new voucher header for the selected patient
        $voucherHeader = VoucherHeader::create([
            'paket_voucher_id' => $paketVoucher->id,
            'patient_id' => $selectedPatients,
            'voucher_header_no' => $request->input('voucher_header_no'),
            'qr_code_header' => $qrCodeBase64Header,
            'purchase_date' => $request->input('purchase_date'),
            'expiry_date' => $request->input('expiry_date'),
            'current_uses' => 0,
            'status' => 'active',
        ]);


        // Loop through total_distribute and generate voucher details
        for ($i = 0; $i < $paketVoucher->total_distribute; $i++) {
            // Increment the running number for each new voucher
            $runningNumber++;

            // Generate a unique voucher number (e.g., V-20230910-001)
            $today = now()->format('Ymd');  // Format: YYYYMMDD
            $voucherNo = $request->input('voucher_header_no') . str_pad($runningNumber, 3, '0', STR_PAD_LEFT);
            // dd($voucherNo);

            // Gather the important data for the QR code (Paket Voucher + Voucher details)
            // $qrData = [
            //     'voucher_header_id' => $voucherHeader->id,
            //     // 'voucher_no' => $voucherNo,
            //     // 'patient_id' => $selectedPatients,
            //     // 'paket_voucher_name' => $paketVoucher->name,
            //     // 'expiry_date' => $request->input('expiry_date'),
            //     // 'purchase_date' => $request->input('purchase_date'),
            //     // 'max_sharing' => $paketVoucher->max_sharing,
            //     // 'total_distribute' => $paketVoucher->total_distribute,
            // ];

            // // Encode the data into JSON format for the QR code
            // $qrDataJson = json_encode($qrData);

            // // Generate the QR code with the encoded data
            // $qrCode = QrCode::format('svg')->size(200)->generate($qrDataJson);
            // $qrCodeBase64 = base64_encode($qrCode);

            // Create a new voucher detail for each distributed voucher
            $voucher =  VoucherDetail::create([
                'voucher_header_id' => $voucherHeader->id,
                'voucher_no' => $voucherNo,
                // 'qr_code' => $qrCodeBase64,
                'issued_to_family_member' => false,
            ]);

            

            

        }

            // Parse the purchase_date and expiry_date from the request
            $purchaseDate = Carbon::parse($request->input('purchase_date'));
            $expiryDate = Carbon::parse($request->input('expiry_date'));
            $months = $purchaseDate->diffInMonths($expiryDate);

            $expired = Carbon::parse($request->input('expiry_date'))->translatedFormat('d F Y');
            $purchased = Carbon::parse($request->input('purchase_date'))->translatedFormat('d F Y');

            $idVoucher = substr($voucherHeader->id, 0, 8);

            // Generate PDF from the HTML layout
            $data = [
                'number' => 1,
                'voucher_id' => $idVoucher,
                'voucher_no' => $request->input('voucher_header_no'),
                'patient_id' => $selectedPatients,
                'patient_name' => $patientName,
                'paket_voucher_name' => $paketVoucher->name,
                'expiry_date' => $expired,
                'purchase_date' => $purchased,
                'months' => $months,
                'max_sharing' => $paketVoucher->max_sharing,
                'total_distribute' => $paketVoucher->total_distribute,
                'qrCode' => $qrCodeBase64Header,
                'tnc' => $paketVoucher->tnc,
            ];

            // Full path to the image
            $imagePath = storage_path('app/public/' . $paketVoucher->image);

            if (file_exists($imagePath)) {
                // Convert image to Base64 format
                $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
                $data['image'] = $imageBase64;
            } else {
                // If the image does not exist, set it to null or provide a default placeholder
                $data['image'] = null;
            }

            $logoPath = storage_path('app/public/' . $paketVoucher->logo_unit);
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
                $data['logo_unit'] = $logoBase64;
            } else {
                $data['logo_unit'] = null;
            }


            // dd($data);
            $pdf = PDF::loadView('voucher-headers.layout', $data);

            $currentDate = Carbon::now()->format('Y-m-d');

            // Create directory in storage for the patient
            $patientDirectory = 'vouchers/' . $patientName . '/' . $currentDate;
            if (!Storage::exists($patientDirectory)) {
                Storage::makeDirectory($patientDirectory);
            }

            // Save PDF to storage
            $voucherFileName = $request->input('voucher_header_no');
            $pdfFileName = $voucherFileName . '.pdf';
            $pdfFilePath = $patientDirectory . '/' . $pdfFileName;

            // Save PDF to storage
            Storage::put($pdfFilePath, $pdf->output());

            // Add file path to the array
            $voucherFilePaths[] = $pdfFilePath;
        

        // Distribute family members across patients, enforcing max_sharing rule
        foreach ($familyMembers as $familyMember) {
            FamilyMember::create([
                'patient_id' => $selectedPatients,
                'name' => $familyMember['name'],
                'birthday' => $familyMember['birthday'],
                'phone' => $familyMember['phone'],
                'email' => $familyMember['email'],
            ]);
        }

        // Save into payment
        $paymentVoucher = Payment::create([
            'patient_id' => $selectedPatients,
            'voucher_header_id' => $voucherHeader->id,
            'amount' => $paketVoucher->amount,
            'purchase_date' => $request->input('purchase_date'),
            'payment_method' => $request->input('payment_method'),
            'no_card' => $request->input('no_card')
        ]);

        $patient = Patient::find($selectedPatients);
        // dd($patient);

        // dd($voucherHeader);
        SendVoucherEmailJob::dispatch($paketVoucher, $voucherHeader, $voucher, $patient, $paymentVoucher, $voucherFilePaths);

        return redirect()->route('vouchers.index')->with('message', 'Vouchers and family members created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoucherHeader  $voucherHeader
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherHeader $voucherHeader, $id)
    {
        $breadcrumbItems = [
            [
                'name' => 'Voucher',
                'url' => route('vouchers.index'),
                'active' => false,
            ],
            [
                'name' => 'Detail',
                'url' => '#',
                'active' => true,
            ],
        ];

        $vouchers = VoucherHeader::with('voucherDetail', 'patient', 'paketVoucher', 'patient.familyMember', 'payment')->findOrFail($id);

        // $table = VoucherDetail::where('voucher_header_id', $id)->whereNotNull('is_used')->get();

        $table = VoucherDetail::with([
            'voucherHeader.patient',
            'voucherHeader.patient.familyMember'
        ])
        ->where('voucher_header_id', $id)
        // ->where('is_used')
        ->orderBy('voucher_no')
        ->get();
        // dd($voucherDetails);

        // dd($table);
        return view('voucher-headers.show', [
            'data' => $vouchers,
            'tables' => $table,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Detail Voucher',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherHeader  $voucherHeader
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $breadcrumbItems = [
            [
                'name' => 'Voucher',
                'url' => route('vouchers.index'),
                'active' => false,
            ],
            [
                'name' => 'Edit',
                'url' => '#',
                'active' => true,
            ],
        ];

        $data = VoucherHeader::with('paketVoucher')->findOrFail($id);
        // dd($data);

        return view('voucher-headers.edit', [
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Edit Voucher',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoucherHeader  $voucherHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:purchase_date',
            'status' => 'required|string|in:active,expired',
        ]);

        $voucherHeader = VoucherHeader::findOrFail($id);

        // Update voucher header details
        $voucherHeader->update([
            'purchase_date' => $request->input('purchase_date'),
            'expiry_date' => $request->input('expiry_date'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('vouchers.index')->with('message', 'Voucher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherHeader  $voucherHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        VoucherHeader::destroy($id);

        return redirect()->route('vouchers.index')->with('message', 'Voucher deleted successfully.');
    }

    public function validateVoucher(Request $request)
    {
        $voucherNo = $request->input('voucher_no');
        // dd($voucherNo);

        // Fetch the voucher header and related details
        $voucherHeader = VoucherHeader::with(['voucherDetail', 'patient', 'paketVoucher', 'patient.familyMember'])
        ->where('voucher_header_no', $voucherNo)
        ->first();
        // $headerVoucher = VoucherHeader::where('voucher_header_no', $voucherNo)->get();
        // dd($voucherHeader);

        // Check if the voucher header exists
        if (!$voucherHeader) {
            return back()->with('error', 'Voucher not found.');
        }

        // Fetch all voucher details for the given header
        $patientVouchers = $voucherHeader->voucherDetail;

        // $patientVouchers = VoucherDetail::with('voucherHeader') // Assuming a relationship is defined
        //         ->whereHas('voucherHeader', function ($query) use ($voucherNo) {
        //             $query->where('voucher_header_no', $voucherNo);
        //         })
        //         ->get();

        // Find all vouchers for the patient
        // $patientVouchers = VoucherDetail::whereHas('voucherHeader', function($query) use ($voucherNo) {
        //     $query->where('voucher_no', $voucherNo);
        // })->get();

        // dd($patientVouchers);

        if ($patientVouchers->isEmpty()) {
            return back()->with('error', 'No vouchers found for this patient.');
        }

        // Total Voucher
        $totalVoucher = $patientVouchers->count();
        // dd($totalVoucher);

        // Remaining voucher
        $remainingVouchers = $patientVouchers->where('is_used', false);

        // dd($remainingVouchers);

        // Calculate total remaining uses
        $totalRemainingUses = $remainingVouchers->count();
        // dd($totalRemainingUses);

        // Calculate total remaining uses across all vouchers
        // $totalRemainingUses = $totalVoucher - count($remainingVouchers);
        // dd($totalRemainingUses);

        // Handle if no remaining uses
        if ($totalRemainingUses <= 0) {
            return back()->with('error', 'All vouchers have been used.');
        }

        // $data = Patient::with('familyMember')->where('id', $patientId)->get();
        

        // $voucherDetails = VoucherHeader::with('voucherDetail', 'patient', 'paketVoucher', 'patient.familyMember')->findOrFail($voucher_header_id);

        // Fetch detailed voucher usage history
        $usedVouchers = VoucherDetail::with([
            'voucherHeader.patient', // Patient data
            'voucherHeader.patient.familyMember', // Family member data
            'voucherHistories' // Voucher usage history
        ])
            ->where('voucher_header_id', $voucherHeader->id)
            ->where('is_used', true) // Only used vouchers
            ->get();

        // dd($usedVouchers);

        // $table =  VoucherDetail::with([
        //             'voucherHeader.patient', // Join the 'patients' table through 'voucher_headers'
        //             'voucherHeader.patient.familyMember', // Join the 'family_members' table through 'patients'
        //             'voucherHistories' // Join the 'voucher_histories' table through 'voucher_details'
        //         ])
        //         ->whereHas('voucherHeader.patient', function ($query) use ($patientId) {
        //             $query->where('id', $patientId); // Filter by patient ID
        //         })
        //         ->where('is_used', true) // Filter for used vouchers
        //         ->get();
        

        // return view('vouchers.validate', [
        //     'data' => $voucherDetails,
        //     'tables' => $table,
        //     'remainingUses' => $totalRemainingUses,
        //     'packageName' => $packageName,
        //     'voucherNo' => $voucherNo,
        //     'purchaseDate' => $purchaseDate,
        //     'expiryDate' => $expiryDate,
        //     'patients' => $patientVouchers
        // ]);
        $family = $voucherHeader->patient->familyMember->name ?? '';
        // Pass data to the view
        return view('vouchers.validate', [
            'family' => $family,
            'tables' => $usedVouchers, // Detailed voucher usage
            'remainingUses' => $totalRemainingUses, // Remaining vouchers count
            'packageName' => $voucherHeader->paketVoucher->name, // Package name
            'voucherNo' => $voucherNo, // Voucher number
            'purchaseDate' => $voucherHeader->purchase_date, // Purchase date
            'expiryDate' => $voucherHeader->expiry_date, // Expiry date
            // 'patients' => $patientVouchers // All vouchers for the header
        ]);
    }

    public function confirm(Request $request)
    {
        // dd($request);
        $voucher_header_no = $request->voucher_header_no;
        $paket_voucher_id = $request->paket_voucher_id;
        $purchase_date = $request->purchase_date;
        $selected_patients = $request->selected_patients;
        $expiry_date = $request->expiry_date ? $request->expiry_date : null;


        // Retrieve the selected_patients from the request and decode it
        $selectedPatientsIds = json_decode($request->input('selected_patients'), true);
        if (is_array($selectedPatientsIds) && !empty($selectedPatientsIds)) {
            // Fetch the patients whose IDs are in the selected patients array
            $patients = Patient::whereIn('id', $selectedPatientsIds)->get();

            // Initialize an empty array to store patients with family members (if any)
            $mergedData = [];

            // Process each patient and merge family data if available
            foreach ($patients as $patient) {
                $patientData = [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'birthday' => $patient->birthday,
                    'phone' => $patient->phone,
                    'email' => $patient->email,
                ];

                // If family members are provided, merge them with the patient data
                if ($request->filled('family_name')) {
                    $familyMembers = [];
                    foreach ($request->family_name as $index => $familyName) {
                        $familyMembers[] = [
                            'name' => $familyName,
                            'birthday' => $request->family_birthday[$index] ?? null,
                            'phone' => $request->family_phone[$index] ?? null,
                            'email' => $request->family_email[$index] ?? null,
                        ];
                    }

                    // Add the family members to the patient data
                    $patientData['family_members'] = $familyMembers;
                } else {
                    // If no family members are provided, add an empty array
                    $patientData['family_members'] = [];
                }

                // Append the patient and family member data to the mergedData array
                $mergedData[] = $patientData;
            }
        }

        $paketName = PackageVoucher::select('id','name', 'amount')->where('id',$paket_voucher_id)->get();
        // dd($paketName[0]['name']);
        $breadcrumbItems = [
            [
                'name' => 'Create Voucher',
                'url' => route('vouchers.create'),
                'active' => false,
            ],
            [
                'name' => 'Create',
                'url' => '#',
                'active' => true,
            ],
        ];


        // dd($mergedData);

        return view('vouchers.confirm',[
            'dataPaket' => $paketName,
            'purchase_date' => $purchase_date,
            'expiry_date' => $expiry_date,
            'breadcrumbItems' => $breadcrumbItems,
            'patients' => $mergedData,
            'pageTitle' => 'Confirm Voucher',
            'voucher_header_no' => $voucher_header_no
        ]);
    }

    public function exportReceiptPdf(Request $request)
    {
        // dd($request);
        
        $voucherData = $request->input('package_name'); 
        $purchase_date = $request->input('purchase_date');
        $expiry_date = $request->input('expiry_date');
        $package_name = $request->input('package_name');
        $patient = Patient::find($request->input('patient_id'));
        $paket_voucher_id = $request->input('paket_voucher_id');
        $voucher_price = $request->input('voucher_price');
        

        if ($request->input('payment_method') !== null) {
            $payment_method = $request->input('payment_method');
        } else {
            $payment_method = Payment::select('payment_method')->where('patient_id', $request->input('patient_id'));
        }

        if ($request->input('no_card') !== null) {
            $no_card = $request->input('no_card');
        } else {
            $no_card = '';
        }

        function cleanVoucherPrice($price) {
            // Remove 'Rp' and dots
            $cleanedPrice = str_replace(['Rp', '.', ','], '', $price);
            
            // Convert to integer
            return (int) $cleanedPrice;
        }

        function terbilang($number) {
            $units = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        
            if ($number < 12) {
                return $units[$number];
            } elseif ($number < 20) {
                return terbilang($number - 10) . ' belas';
            } elseif ($number < 100) {
                return terbilang(floor($number / 10)) . ' puluh ' . terbilang($number % 10);
            } elseif ($number < 200) {
                return 'seratus ' . terbilang($number - 100);
            } elseif ($number < 1000) {
                return terbilang(floor($number / 100)) . ' ratus ' . terbilang($number % 100);
            } elseif ($number < 2000) {
                return 'seribu ' . terbilang($number - 1000);
            } elseif ($number < 1000000) {
                return terbilang(floor($number / 1000)) . ' ribu ' . terbilang($number % 1000);
            } elseif ($number < 1000000000) {
                return terbilang(floor($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
            } else {
                return 'Angka terlalu besar';
            }
        }

        $numericVoucherPrice = cleanVoucherPrice($voucher_price);

        $terbilang = terbilang($numericVoucherPrice);

        $paketDetail = PackageVoucher::where('id', $paket_voucher_id)->get();
        // dd($paketDetail[0]['total_distribute']);
        // Load a view and pass data to the view
        $pdf = PDF::loadView('vouchers.bukti-bayar', [
            'voucherData' => $voucherData,
            'purchase_date' => $purchase_date,
            'expiry_date' => $expiry_date,
            'package_name' => $package_name,
            'patient' => $patient,
            'voucher_price' => $voucher_price,
            'payment_method' => $payment_method,
            'no_card' => $no_card,
            'paketDetail' => $paketDetail,
            'terbilang' => $terbilang,
        ]);

        // Stream or download the generated PDF
        return $pdf->stream('bukti_bayar.pdf');
    }

    public function testVoucher()
    {
        dd(route('evoucher'));
    }

    private function sendToGmail()
    {

    }
    
}
