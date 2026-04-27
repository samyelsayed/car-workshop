<?php

namespace App\Http\Requests\Api\Admin\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|string|in:initial,detailed,follow_up',
            'inspectionDate' => 'sometimes|date',
            'findings' => 'sometimes|string',
            'estimatedCost' => 'sometimes|numeric|min:0',
            'notes' => 'sometimes|string|nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $mapped = [];

        if ($this->has('inspectionDate')) {
            $mapped['inspection_date'] = $this->inspectionDate;
        }
        if ($this->has('estimatedCost')) {
            $mapped['estimated_cost'] = $this->estimatedCost;
        }

        $this->merge($mapped);
    }
}
