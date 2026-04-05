<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
//         return [

// //

//            'first_name'=>['required','min:3','max:50'],
//            'last_name'=>['required','min:3','max:50'],
//            'email'=>['required','unique:users','email'],
//            'phone'=>['regex:/^01[0-2,5,9]{1}[0-9]{8}$/','required','unique:user_mobiles,mobile_number'],
//             'password' => ['required','confirmed', Password::min(8)->numbers()->mixedCase()->symbols()],
//         ];


return [
            // حولناهم لـ camelCase هنا
            'firstName' => ['required', 'string', 'max:50'],
            'lastName'  => ['required', 'string', 'max:50'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'phone'=>['regex:/^01[0-2,5,9]{1}[0-9]{8}$/','required','unique:user_mobiles,mobile_number'],        ];

    }

    public function attributes(): array
    {
        return [
            'firstName' => 'first name',
            'lastName'  => 'last name',
        ];
    }
}
