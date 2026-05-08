<?php

namespace App\Http\Requests\Api\Admin\Inspection;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInspectionRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
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
            'type' => [
                'sometimes',
                'string',
                Rule::in(['initial', 'detailed', 'follow_up'])
            ],

            'inspection_date' => ['sometimes', 'date'],
            'findings'        => ['sometimes', 'string'],
            'estimated_cost'  => ['sometimes', 'numeric', 'min:0'],
            'notes'           => ['sometimes', 'nullable', 'string'],
        ];
    }
}
