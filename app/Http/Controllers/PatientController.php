<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PackageVoucher;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

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

        $data = Patient::all();
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
    public function store(StorePatientRequest $request)
    {
        //
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
}
