<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\PackageVoucher;
use App\Models\Patient;
use App\Models\Voucher;
use App\Models\FamilyMember;
use App\Models\VoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        dd('ss');
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
        $data = Voucher::with('paketVoucher','patient');
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

        return view('vouchers.index', [
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
        dd('aacascd');
        $selectedPatientId = session('patient_id');
        // dd($selectedPatientId);

        // Get the selected patient ID if returning from the update form
        // $selectedPatientId = $request->input('patient_id');
        // dd($selectedPatientId);

        // If selected_patients are passed as JSON, decode it (from previous form submission)
        $selectedPatients = $request->has('selected_patients') ? json_decode($request->input('selected_patients'), true) : [];

        // $selectedPatients = json_decode($request->input('selected_patients'), true);
        // $patients = Patient::whereIn('id', $selectedPatients)->get();

        $patients = collect();

        // If have selected patients, fetch them from the database
        if (!empty($selectedPatients)) {
            $patients = Patient::whereIn('id', $selectedPatients)->get();
        }

        // If returning from update form with a specific patient ID, fetch that patient
        if ($selectedPatientId) {
            $patients = Patient::where('id', $selectedPatientId)->get();
        }
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
        // dd($packageVoucher);

        // setup for voucher number with running number
        $totalVoucher = Voucher::count();
        $runningNumber = $totalVoucher + 1;
        $today = now()->format('Ymd');
        $voucherNo = "V{$today}" . str_pad($runningNumber, 3, '0', STR_PAD_LEFT);

        // $patients = Patient::whereIn('id', $selectedPatients)->get();
// dd($patients);
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
     * @param  \App\Http\Requests\StoreVoucherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'paket_voucher_id' => 'required|exists:package_vouchers,id',
            'voucher_no' => 'nullable|unique:vouchers,voucher_no',
            'purchase_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:purchase_date',
            'selected_patients' => 'required|json', // Selected patients in JSON format
            'family_name' => 'nullable|array',      // Array of family member names
            'family_birthday' => 'nullable|array',
            'family_phone' => 'nullable|array',     // Array of family member phones
            'family_email' => 'nullable|array',     // Array of family member emails
        ]);

        // Find the selected Paket Voucher
        $paketVoucher = PackageVoucher::find($request->paket_voucher_id);

        // Decode the selected patients from the request
        $selectedPatients = json_decode($request->input('selected_patients'), true);
        // dd($paketVoucher);

        // Gather family members from the request
        $familyMembers = [];
        if ($request->has('family_name')) {
            $familyNames = $request->input('family_name');
            $familyPhones = $request->input('family_phone');
            $familyEmails = $request->input('family_email');
            $familyBirthdays = $request->input('family_birthday');

            // Combine family member data into an array
            for ($i = 0; $i < count($familyNames); $i++) {
                $familyMembers[] = [
                    'name' => $familyNames[$i],
                    'birthday' => $familyBirthdays[$i],
                    'phone' => $familyPhones[$i],
                    'email' => $familyEmails[$i],
                ];
            }
        }

        // Get the latest voucher number in the system for running number
        $lastVoucher = Voucher::where('paket_voucher_id', $paketVoucher->id)
                            ->orderBy('voucher_no', 'desc')
                            ->first();

        // Start the running number from the last voucher number, or default to 0
        $runningNumber = $lastVoucher ? intval(substr($lastVoucher->voucher_no, -3)) : 0;


        // Loop through total checklist patient
        foreach ($selectedPatients as $patientId) {
            // dd($patientId);
            // Loop through total_distribute and generate vouchers for patients
            for ($i=0; $i < $paketVoucher->total_distribute; $i++) {
                // Increment the running number for each new voucher
                $runningNumber++;

                // Generate a unique voucher number (e.g., V-20230910-001)
                $today = now()->format('Ymd');  // Format: YYYYMMDD
                $voucherNo = "V{$today}" . str_pad($runningNumber, 3, '0', STR_PAD_LEFT);
                // Gather the important data for the QR code (Paket Voucher + Voucher details)
                $qrData = [
                    'voucher_no' => $voucherNo,
                    'patient_id' => $patientId,
                    'paket_voucher_name' => $paketVoucher->name,
                    'expiry_date' => $request->input('expiry_date'),
                    'purchase_date' => $request->input('purchase_date'),
                    'max_sharing' => $paketVoucher->max_sharing,
                    'total_distribute' => $paketVoucher->total_distribute,
                ];

                // Encode the data into JSON format for the QR code
                $qrDataJson = json_encode($qrData);
                // Generate the QR code with the encoded data
                $qrCode = QrCode::format('svg')->size(200)->generate($qrDataJson);
                $qrCodeBase64 = base64_encode($qrCode);

                Voucher::create([
                    'voucher_no' => $voucherNo,
                    'paket_voucher_id' => $paketVoucher->id,
                    'patient_id' => $patientId,
                    'purchase_date' => $request->input('purchase_date'),
                    'expiry_date' => $request->input('expiry_date'),
                    'max_uses' => 1,
                    'current_uses' => 0,
                    'status' => 'active',
                    'qr_code' => $qrCodeBase64, // Store the generated QR code
                ]);
            }
        }

        // Distribute family members across patients, enforcing max_sharing rule
        $familyMemberIndex = 0;
        foreach ($selectedPatients as $patientId) {
            // Get the max sharing limit from the paket_voucher
            $maxSharing = $paketVoucher->max_sharing;
            // dd(count($familyMembers));

            // Add family members to this patient, up to the max_sharing limit
            for ($i = 0; $i < $maxSharing && $familyMemberIndex < count($familyMembers); $i++) {
                $familyMember = $familyMembers[$familyMemberIndex];

                // Create a family member record for the current patient
                FamilyMember::create([
                    'patient_id' => $patientId,
                    'name' => $familyMember['name'],
                    'birthday' => $familyMember['birthday'],
                    'phone' => $familyMember['phone'],
                    'email' => $familyMember['email'],
                ]);

                // Move to the next family member
                $familyMemberIndex++;
            }

            // Stop if all family members have been assigned
            if ($familyMemberIndex >= count($familyMembers)) {
                break;
            }
        }

        return redirect()->route('vouchers.index')->with('message', 'Vouchers and family members created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbItems = [
            [
                'name' => 'Package Voucher',
                'url' => route('vouchers.index'),
                'active' => false,
            ],
            [
                'name' => 'View',
                'url' => '#',
                'active' => true,
            ],
        ];

        $data = Voucher::findOrFail($id);

        return view('vouchers.show', [
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'View Voucher',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
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

        $data = Voucher::findOrFail($id);

        return view('vouchers.edit', [
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Edit Voucher',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVoucherRequest  $request
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVoucherRequest $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->validated());

        return redirect()->route('vouchers.index')->with('message', 'Voucher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Voucher::destroy($id);

        return redirect()->route('vouchers.index')->with('message', 'Voucher deleted successfully.');
    }

    

    public function validateVoucher(Request $request)
    {
        // dd($request);
        $patientId = $request->input('patient_id');
        $voucherNo = $request->input('voucher_no');
        $packageName = $request->input('paket_voucher_name');
        $purchaseDate = $request->input('purchase_date');
        $expiryDate = $request->input('expiry_date');



        // Find all vouchers for the patient
        $patientVouchers = VoucherDetail::whereHas('voucherHeader', function($query) use ($patientId) {
            $query->where('patient_id', $patientId);
        })->get();

        if ($patientVouchers->isEmpty()) {
            return back()->with('error', 'No vouchers found for this patient.');
        }

        // Total Voucher
        $totalVoucher = count($patientVouchers);

        // Remaining voucher
        $remainingVoucher = VoucherDetail::where('is_used', '=', true)->get();



        // Calculate total remaining uses across all vouchers
        $totalRemainingUses = $totalVoucher - count($remainingVoucher);

        // Handle if no remaining uses
        if ($totalRemainingUses <= 0) {
            return back()->with('error', 'All vouchers have been used.');
        }



        return view('vouchers.validate', [
            // 'data' => $data,
            'remainingUses' => $totalRemainingUses,
            'packageName' => $packageName,
            'voucherNo' => $voucherNo,
            'purchaseDate' => $purchaseDate,
            'expiryDate' => $expiryDate,
            'patients' => $patientVouchers
        ]);
    }

}
