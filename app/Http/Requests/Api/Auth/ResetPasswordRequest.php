<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // استدعاء كلاس الباسورد

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
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')
            ],

            'password' => [
                'required',
                'confirmed',
                // استخدام الـ Rule Object بدل الـ Regex المعقد
                Password::min(8)
                    ->letters()      // لازم حروف
                    ->mixedCase()    // حروف كبيرة وصغيرة
                    ->numbers()      // أرقام
                    ->symbols()      // رموز خاصة (@$!%*?&#)
                    ->uncompromised() // اختياري: بيشيك لو الباسورد ده اتسرب قبل كدة في اختراقات عالمية
            ],

            'device_name' => ['required', 'string'],
            'token' => ['required', 'string']
        ];
    }
}
