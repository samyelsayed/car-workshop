<?php

namespace App\Http\Requests\Api\Admin\WorkProgress;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderId' => 'required|exists:orders,id',
            'stage' => 'required|string|max:50',
            'status' => 'sometimes|in:not_started,in_progress,completed',
            'startedAt' => 'nullable|date',
            'completedAt' => 'nullable|date|after_or_equal:startedAt',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'order_id' => $this->orderId,
            'started_at' => $this->startedAt,
            'completed_at' => $this->completedAt,
        ]);
    }


}
