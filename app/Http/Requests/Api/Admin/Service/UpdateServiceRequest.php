<?php

namespace App\Http\Requests\Api\Admin\Service;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'basePrice' => 'base_price',
        'isActive'  => 'is_active',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array

    {
         $serviceId = $this->route('id') ?? $this->route('service');
        return [

            'name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('services', 'name')
                    ->ignore($serviceId) // 🔥 كدة الـ ignore شغال صح بنسبة 100%
                    ->whereNull('deleted_at')
            ],

            'description' => ['sometimes', 'string', 'min:10'],
            'base_price'  => ['sometimes', 'numeric', 'min:0'],
            'image'       => ['sometimes', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
