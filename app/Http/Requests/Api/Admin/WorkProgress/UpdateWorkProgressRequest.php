<?php

namespace App\Http\Requests\Api\Admin\WorkProgress;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkProgressRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
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
            'stage' => ['sometimes', 'string', 'max:50'],

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
}
