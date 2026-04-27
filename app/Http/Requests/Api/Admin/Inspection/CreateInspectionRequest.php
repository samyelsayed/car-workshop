<?php

namespace App\Http\Requests\Api\Admin\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class CreateInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderId' => 'required|exists:orders,id',
            'type' => 'required|string|in:initial,detailed,follow_up',
            'inspectionDate' => 'required|date',
            'findings' => 'required|string',
            'estimatedCost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'order_id' => $this->orderId,
            'inspection_date' => $this->inspectionDate,
            'estimated_cost' => $this->estimatedCost ?? 0,
        ]);
    }
}
