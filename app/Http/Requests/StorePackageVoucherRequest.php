<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageVoucherRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_vouchers' => 'nullable|integer|min:1',
            'total_distribute' => 'nullable|integer|min:1|max:total_voucher',
            'voucher_type' => 'required|string',
            'max_sharing' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tnc' => 'required|string',
            'amount' => 'nullable'
        ];
    }
}
