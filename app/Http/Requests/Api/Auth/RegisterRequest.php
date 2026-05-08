<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')
            ],
            'password' => ['required'],
            'device_name' => ['required', 'string']
        ];
    }
}
