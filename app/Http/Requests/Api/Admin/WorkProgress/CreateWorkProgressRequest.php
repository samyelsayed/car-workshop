<?php

namespace App\Http\Requests\Api\Admin\WorkProgress;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateWorkProgressRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'orderId'     => 'order_id',
        'startedAt'   => 'started_at',
        'completedAt' => 'completed_at',
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

            'stage' => ['required', 'string', 'max:50'],

            'status' => [
                'sometimes',
                Rule::in(['not_started', 'in_progress', 'completed'])
            ],

            'started_at' => ['nullable', 'date'],

            'completed_at' => [
                'nullable',
                'date',
                'after_or_equal:started_at'
            ],

            'notes' => ['nullable', 'string'],
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->filled('status')) {
            $this->merge(['status' => 'not_started']);
        }
    }
}
