<?php

namespace App\Http\Requests\Api\Admin\Notification;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:order_update,payment,general,promotion',
            'title' => 'required|string|max:100',
            'message' => 'required|string',
            'userRole' => 'nullable|in:user,admin',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('userRole')) {
            $this->merge([
                'user_role' => $this->userRole,
            ]);
        }
    }
}