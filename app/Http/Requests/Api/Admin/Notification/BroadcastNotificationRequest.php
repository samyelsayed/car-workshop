<?php

namespace App\Http\Requests\Api\Admin\Notification;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BroadcastNotificationRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'orderId'        => 'order_id',
        'inspectionDate' => 'inspection_date',
        'estimatedCost'  => 'estimated_cost',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['initial', 'detailed', 'follow_up'])
            ],
            'inspection_date' => ['required', 'date'],
            'findings'        => ['required', 'string'],
            'estimated_cost'  => ['nullable', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string'],
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->filled('estimated_cost')) {
            $this->merge(['estimated_cost' => 0]);
        }
    }
}
