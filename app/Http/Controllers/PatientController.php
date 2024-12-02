<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PackageVoucher;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbItems = [
            [
                'name' => 'Patient',
                'url' => route('patient.index'),
                'active' => false
            ],
            [
                'name' => 'List',
                'url' => '#',
                'active' => true
            ],
        ];

        $data = Patient::orderBy('created_at', 'desc')->get();
        $paketVoucher = PackageVoucher::all();

        return view('patients.index',[
            'data' => $data,
            'paketVouchers' => $paketVoucher,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Patients'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePatientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patients = collect();
        // dd($request->patients);

        DB::beginTransaction();
        try {
            foreach ($request->patients as $patientData) {
                // Validate each patient data individually
                $validatedData = $request->validate([
                    'rm_no' => 'nullable|string|unique:patients,rm_no',
                    'name' => 'nullable|string|max:255',
                    'birthday' => 'nullable|date|before:today',
                    'email' => 'nullable|email',
                    'phone' => 'nullable|string|max:15',
                ]);

                // Create patient record
                $patient = Patient::create([
                    'rm_no' => $patientData['no_rm'],
                    // 'registration_no' => $patientData['registration_no'],
                    'name' => $patientData['name_real'],
                    'birthday' => \Carbon\Carbon::parse($patientData['tgl_lahir'])->format('Y-m-d'),
                    'email' => $patientData['email'] ?? null,
                    'phone' => $patientData['phone'] ?? null,
                ]);

                $patients->push($patient->id);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'selected_patients' => $patients], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::with('familyMember')->findOrFail($id);
        // dd($patient->familyMembers);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePatientRequest  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $patient = Patient::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'family_name' => 'nullable',
            'family_birthday' => 'nullable',
            'family_phone' => 'nullable',
            'family_email' => 'nullable',
        ]);

        // Update patient details
        $patient->update([
            'name' => $request->input('name'),
            'birthday' => $request->input('birthday'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        // Update family members
        $familyNames = $request->input('family_name', []);
        // dd($familyNames);
        $familyBirthdays = $request->input('family_birthday', []);
        $familyPhones = $request->input('family_phone', []);
        $familyEmails = $request->input('family_email', []);

        // Delete existing family members
        $patient->familyMember()->delete();

        // Re-create family members
        // for ($i = 0; $i < count($familyNames); $i++) {
            $patient->familyMember()->create([
                'name' => $familyNames,
                'birthday' => $familyBirthdays,
                'phone' => $familyPhones,
                'email' => $familyEmails,
            ]);
        // }

        return redirect()->route('patient.index')->with('message', 'Patient and family members updated successfully');
    }

    // public function update(Request $request, $id)
    // {
    //     // dd($request);
    //     // Validate the incoming data
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'birthday' => 'required|date',
    //         'phone' => 'required|string|max:15',
    //         'email' => 'required|email|max:255',
    //     ]);

    //     // Fetch the patient by ID
    //     $patient = Patient::findOrFail($id);

    //     // Update patient details
    //     $patient->name = $request->input('name');
    //     $patient->birthday = $request->input('birthday');
    //     $patient->phone = $request->input('phone');
    //     $patient->email = $request->input('email');
    //     $patient->save();

    //     // Redirect back with success message
    //     return redirect()->route('vouchers.create')
    //     ->with('message', 'Patient updated successfully')
    //     ->with('patient_id', $patient->id);
    // }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }

    // public function searchPatient(Request $request)
    // {
    //     // dd($request->query('rm_no'));
    //     $baseUrl = 'http://192.168.9.9/devel/';
    //     $params = [
    //         'mod' => 'api',
    //         'cmd' => 'get_patient',
    //         // 'no_rm' => $request->query('rm_no'),
    //         'name_pasien' => 'anang',
    //         'return_type' => 'json',
    //     ];

    //     // try {
    //         $response = Http::withHeaders([
    //                 'Accept' => 'application/json',
    //                 'Content-Type' => 'application/json',
    //                 'Authorization' => 'Basic cnNjaXB1dHJhOnJzY2lwdXRyYQ==',
    //                 'Cookie' => 'CIPDEV=b2fd2eqqpqh9v3e6d91fd56vk3'
    //             ])
    //             ->get($baseUrl, $params);
    //             dd($response);
    //         if ($response->successful()) {
    //             $data = $response->json();
                
    //             return response()->json(['status' => 'success', 'patients' => $data]);
    //         } else {
    //             return response()->json(['status' => 'error', 'message' => 'Failed to fetch patients.'], $response->status());
    //         }
    //     // } catch (\Exception $e) {
    //     //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     // }
    // }
    

    public function searchPatient(Request $request)
    {
        // dd($request);
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic cnNjaXB1dHJhOnJzY2lwdXRyYQ==',
        ];

        // Construct URL with query parameters
        $url = 'http://192.168.9.9/devel/?mod=api&cmd=get_patient&return_type=json' .
            // '&no_rm=' . $request->query('rm_no') .
            '&nama_pasien=' . urlencode($request->name) . 
            '&tgl_lahir=' . urlencode($request->bod);
            // dd($url);

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                // dd($data);
                return response()->json(['status' => 'success', 'patients' => $data]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to fetch patients.'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}
