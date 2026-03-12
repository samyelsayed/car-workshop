<?php

namespace App\Http\Requests\Api\Phone;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile_number' => ['required','regex:/^01[0-2,5,9]{1}[0-9]{8}$/', 'unique:user_mobiles,mobile_number'] // التشييك على جدول الموبايلات
        ];
    }
}
