<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
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
            'voucher_no' => 'sometimes|string|unique:vouchers,voucher_no,' . $this->voucher,
            'expiry_date' => 'sometimes|date|after:purchase_date',
            'max_uses' => 'sometimes|integer|min:1',
            'current_uses' => 'sometimes|integer|min:0|max:' . $this->input('max_uses', 1),
            'status' => 'sometimes|in:active,expired,redeemed',
        ];
    }
}
