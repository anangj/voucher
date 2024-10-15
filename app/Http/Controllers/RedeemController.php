<?php

namespace App\Http\Controllers;

use App\Models\Redeem;
use App\Models\Voucher;
use App\Models\VoucherHistory;
use App\Http\Requests\StoreRedeemRequest;
use App\Http\Requests\UpdateRedeemRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedeemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('redeems.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $patientId = $request->input('patient_id');
        $voucherNo = $request->input('voucher_no');
        $packageName = $request->input('paket_voucher_name');
        $purchaseDate = $request->input('purchase_date');
        $expiryDate = $request->input('expiry_date');
        // dd($request);

        // Find all vouchers for the patient
        $patientVouchers = Voucher::where('patient_id', $patientId)->get();

        if ($patientVouchers->isEmpty()) {
            return back()->with('error', 'No vouchers found for this patient.');
        }

        // Calculate total remaining uses across all vouchers
        $totalRemainingUses = $patientVouchers->sum(function ($voucher) {
            return $voucher->max_uses - $voucher->current_uses;
        });

        // dd($totalRemainingUses);

        // Handle if no remaining uses
        if ($totalRemainingUses <= 0) {
            return back()->with('error', 'All vouchers have been used.');
        }

        $data = Voucher::with('paketVoucher', 'patient')->where('voucher_no', $voucherNo)->get();

        $patients =  DB::table('vouchers as v')
        ->join('patients as p', 'v.patient_id', '=', 'p.id') // Inner join on patients
        ->leftJoin('family_members as fm', 'fm.patient_id', '=', 'p.id') // Left join on family members
        ->where('v.voucher_no', $voucherNo)
        ->select(
            'p.id as id',
            'p.name as patient_name',
            'p.birthday as patient_birthday',
            'p.phone as patient_phone',
            'p.email as patient_email',
            'fm.name as family_name',
            'fm.birthday as family_birthday',
            'fm.phone as family_phone',
            'fm.email as family_email'
        )
        ->get();
        
dd($patients);
        // // Redeem one use (find the first voucher with remaining uses)
        // foreach ($vouchers as $voucher) {
        //     if ($voucher->current_uses < $voucher->max_uses) {
        //         // Deduct one use
        //         $voucher->current_uses += 1;
        //         $voucher->save();

        //         // Optionally log the redemption
        //         // VoucherHistory::create([
        //         //     'voucher_id' => $voucher->id,
        //         //     'action' => 'redeemed',
        //         //     'performed_by' => $patientId,
        //         //     'timestamp' => now(),
        //         // ]);

        //         return back()->with('message', 'One voucher redeemed successfully.');
        //     }
        // }

        // If we reach here, all vouchers are fully used
        // return back()->with('error', 'No available vouchers to redeem.');
// dd($data);
        return view('redeems.create',[
            'data' => $data,
            'remainingUses' => $totalRemainingUses,
            'packageName' => $packageName,
            'voucherNo' => $voucherNo,
            'purchaseDate' => $purchaseDate,
            'expiryDate' => $expiryDate,
            'patients' => $patients
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRedeemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRedeemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Redeem  $redeem
     * @return \Illuminate\Http\Response
     */
    public function show(Redeem $redeem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Redeem  $redeem
     * @return \Illuminate\Http\Response
     */
    public function edit(Redeem $redeem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRedeemRequest  $request
     * @param  \App\Models\Redeem  $redeem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRedeemRequest $request, Redeem $redeem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Redeem  $redeem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Redeem $redeem)
    {
        //
    }
}
