<?php

namespace App\Http\Requests\Api\Admin\Notification;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendNotificationRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'userRole' => 'user_role',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(['order_update', 'payment', 'general', 'promotion'])
            ],
            'title' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'user_role' => [
                'nullable',
                Rule::in(['user', 'admin'])
            ],
        ];
    }
}
