<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
           'first_name'=>['required','min:3','max:50'],
           'last_name'=>['required','min:3','max:50'],
           'email'=>['required','unique:users','email'],
           'phone'=>['regex:/^01[0-2,5,9]{1}[0-9]{8}$/','required','unique:user_mobiles,mobile_number'],
           'password'=>['required','confirmed','string','min:8']
        ];
    }
}
