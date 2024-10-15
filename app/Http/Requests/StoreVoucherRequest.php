<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'voucher_no' => 'nullable|string|unique:vouchers,voucher_no',
            // 'paket_voucher_id' => 'nullable|uuid|exists:package_vouchers,id',
            // 'patient_id' => 'nullable|uuid|exists:patients,id',
            // 'purchase_date' => 'nullable|date',
            // 'expiry_date' => 'required|date|after:purchase_date',
            // 'max_uses' => 'required|integer|min:1',
        ];
    }
}
