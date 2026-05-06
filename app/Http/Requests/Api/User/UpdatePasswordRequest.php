<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword' => 'required|string',
            'newPassword' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ],
            'deviceName' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'current_password' => $this->currentPassword,
            'new_password' => $this->newPassword,
            'device_name' => $this->deviceName ?? 'web',
        ]);
    }

    public function attributes(): array
    {
        return [
            'currentPassword' => 'current password',
            'newPassword' => 'new password',
        ];
    }
}
