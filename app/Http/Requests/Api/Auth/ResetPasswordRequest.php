<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        'deviceName' => 'device_name',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'phone' => [
                'required',
                'regex:/^01[0-2,5,9]{1}[0-9]{8}$/',
                'unique:user_mobiles,mobile_number'
            ],
            'device_name' => ['nullable', 'string', 'max:100']
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'device_name' => 'device name',
        ];
    }
}
