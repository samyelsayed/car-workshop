<?php

namespace App\Http\Requests\Api\User;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    use MapsCamelCase;

    // تعريف الخريطة لتحويل الحقول تلقائياً
    protected array $map = [
        'currentPassword' => 'current_password',
        'newPassword'     => 'new_password',
        'deviceName'      => 'device_name',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],

            'new_password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols() // يفضل إضافة الرموز لزيادة الأمان
                    // ->uncompromised(), // حماية إضافية ضد التسريبات
            ],

            'device_name' => ['nullable', 'string'],
        ];
    }

    protected function passedValidation()
    {
        // وضع قيمة افتراضية للـ device_name في حالة كان فارغاً
        if (!$this->has('device_name')) {
            $this->merge(['device_name' => 'web']);
        }
    }

    public function attributes(): array
    {
        return [
            'currentPassword' => 'current password',
            'newPassword'     => 'new password',
        ];
    }
}
