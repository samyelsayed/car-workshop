<?php

namespace App\Http\Requests\Api\Admin\Notification;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BroadcastNotificationRequest extends FormRequest
{
    use MapsCamelCase;

    // الخرائط لتحويل الـ camelCase من الفرونت إند لـ snake_case في الداتابيز
    protected array $map = [
        'orderId'  => 'order_id',
        'userRole' => 'user_role', // الخريطة الجديدة 🎯
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id'  => ['nullable', 'integer', 'exists:orders,id'],
            'title'     => ['required', 'string', 'max:255'],
            'message'   => ['required', 'string'],
            'type'      => ['required', 'string', 'max:50'],

            // تحديد الفئة المستهدفة (إجباري عشان الـ Service تشتغل صح)
            'user_role' => [
                'required',
                'string',
                Rule::in(['all', 'client','admin', 'technician']) // الأدوار المتاحة عندك في السيستم
            ],
        ];
    }
}
