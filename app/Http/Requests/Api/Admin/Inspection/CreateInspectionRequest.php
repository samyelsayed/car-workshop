<?php

namespace App\Http\Requests\Api\Admin\Inspection;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInspectionRequest extends FormRequest
{
    use MapsCamelCase;

    /**
     * تعريف خريطة التحويل
     */
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
            // 1. استخدام Rule::exists للتأكد من وجود الطلب في قاعدة البيانات
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')
            ],

            // 2. استخدام Rule::in للأنواع المحددة
            'type' => [
                'required',
                'string',
                Rule::in(['initial', 'detailed', 'follow_up'])
            ],

            'inspection_date' => ['required', 'date'],
            'findings'        => ['required', 'string'],

            // 3. التحقق من التكلفة التقديرية
            'estimated_cost'  => ['nullable', 'numeric', 'min:0'],

            'notes'           => ['nullable', 'string'],
        ];
    }

    /**
     * التعامل مع القيم الافتراضية (بدل ما كانت في الـ merge القديم)
     */
    protected function passedValidation(): void
    {
        // لو التكلفة مبعوتة null أو مش موجودة، بنخليها 0 في الـ request
        if (!$this->filled('estimated_cost')) {
            $this->merge(['estimated_cost' => 0]);
        }
    }
}
