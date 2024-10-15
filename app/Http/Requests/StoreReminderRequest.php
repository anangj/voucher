<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
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
            'patient_id' => 'required|uuid|exists:patients,id',
            'voucher_id' => 'required|uuid|exists:vouchers,id',
            'reminder_type' => 'required|string|max:50',
            'scheduled_at' => 'required|date',
        ];
    }
}
