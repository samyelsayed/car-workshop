<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols() // يفضل إضافة الرموز لزيادة الأمان
                    ->uncompromised(), // حماية إضافية ضد التسريبات
            ],
            'device_name' => ['required', 'string']
        ];
    }
}
