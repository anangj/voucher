<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoucherDetail;
use App\Models\VoucherHeader;
use App\Models\VoucherHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RedeemController extends Controller
{
    public function redeemVoucher(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'voucher_no' => 'required|string|exists:voucher_details,voucher_no',
            'rm_no' => 'required|string',
            'registration_no' => 'required|string',
            'patient_name' => 'required|string',
            'bill_no' => 'required|string',
            'bill_date' => 'required|string'
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Retrieve the voucher by its number
        $voucher = VoucherDetail::where('voucher_no', $request->voucher_no)
                    ->where('is_used', false)
                    ->first();

        // Check if voucher exists and hasn't been used
        if (!$voucher) {
            return response()->json(['error' => 'Voucher not found or already used.'], 404);
        }

        // Check voucher expiry date
        $voucherHeader = VoucherHeader::find($voucher->voucher_header_id);
        if ($voucherHeader && now()->greaterThan($voucherHeader->expiry_date)) {
            return response()->json(['error' => 'Voucher has expired.'], 400);
        }

        // Mark the voucher as used
        $voucher->is_used = true;
        $voucher->using_date = now();
        $voucher->save();

        VoucherHistory::create([
            'voucher_detail_id' => $voucher->id,
            'voucher_no' => $request->input('voucher_no'),
            'performed_by' => $request->input('patient_name'),
            'action' => 'redeemed',
            'bill_no' => $request->input('bill_no'),
            'bill_date' => $request->input('bill_date'),
        ]);

        return response()->json([
            'message' => 'Voucher successfully redeemed.',
            'voucher_no' => $voucher->voucher_no,
            'redeemed_at' => $voucher->using_date,
        ], 200);
    }
}