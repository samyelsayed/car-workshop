<?php

namespace App\Http\Requests\Api\Admin\WorkProgress;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stage' => 'sometimes|string|max:50',
            'status' => 'sometimes|in:not_started,in_progress,completed',
            'startedAt' => 'nullable|date',
            'completedAt' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $mapped = [];

        if ($this->has('startedAt')) {
            $mapped['started_at'] = $this->startedAt;
        }
        if ($this->has('completedAt')) {
            $mapped['completed_at'] = $this->completedAt;
        }

        $this->merge($mapped);
    }


}
