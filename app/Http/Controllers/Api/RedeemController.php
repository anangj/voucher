<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoucherDetail;
use App\Models\VoucherHeader;
use App\Models\VoucherHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RedeemApiController extends Controller
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

        if ($voucherHeader && now()->greaterThanOrEqualTo($voucherHeader->expiry_date)) {
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

        // Check if all voucher details are used
        $allVouchersUsed = VoucherDetail::where('voucher_header_id', $voucher->voucher_header_id)
            ->where('is_used', false)
            ->doesntExist();

        if ($allVouchersUsed) {
            $voucherHeader->status = 'all redeem';
            $voucherHeader->save();
        }

        return response()->json([
            'message' => 'Voucher successfully redeemed.',
            'voucher_no' => $voucher->voucher_no,
            'redeemed_at' => $voucher->using_date,
        ], 200);
    }

    public function getListVoucher(Request $request)
    {
        // Validate the input
        $request->validate([
            'voucher_no' => 'required|string|max:255', // Ensure voucher_no is provided and valid
        ]);

        $voucherNo = $request->input('voucher_no');

        // Retrieve voucher details with relationships
        $voucherHeader = VoucherHeader::with([
            'voucherDetail' => function ($query) {
                $query->where('is_used', false); // Unused vouchers
            },
        ])
            ->where('voucher_header_no', $voucherNo)
            ->where('expiry_date', '>=', now()->format('Y-m-d')) // Ensure the voucher is not expired
            ->first();

        if ($voucherHeader) {
            $voucherDetails = $voucherHeader->voucherDetail;

            // Check if there are unused vouchers
            if ($voucherDetails->isEmpty()) {
                return response()->json(['message' => 'No unused vouchers available or the voucher has expired.'], 404);
            }
        } else {
            return response()->json(['message' => 'Voucher not found or expired.'], 404);
        }

        // Format response
        $response = [
            'voucher_details' => $voucherHeader->voucherDetail->map(function ($detail) {
                return [
                    'voucher_no' => $detail->voucher_no
                ];
            }),
        ];

        // Return JSON response
        return response()->json([
            'status' => 'success',
            'data' => $response,
        ], 200);
    }
}
