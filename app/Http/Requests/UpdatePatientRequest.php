<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'rm_no' => 'sometimes|string|unique:patients,rm_no,' . $this->patient,
            'registration_no' => 'sometimes|string|unique:patients,registration_no,' . $this->patient,
            'name' => 'sometimes|string|max:255',
            'birthday' => 'sometimes|date|before:today',
            'email' => 'sometimes|email|unique:patients,email,' . $this->patient,
            'phone' => 'sometimes|string|max:15',
        ];
    }
}
