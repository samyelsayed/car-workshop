<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'deviceName' => 'device_name',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'device_name' => 'device name',
        ];
    }
}
