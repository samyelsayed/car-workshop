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
        return [
            // الـ ID غالباً بيجي في الـ URL (Route Parameter) بس لو هتبعتة في الـ Body خليه موجود
            'id' => ['required', Rule::exists('services', 'id')],

            'name' => ['sometimes','string','max:20',
                Rule::unique('services', 'name')
                    ->ignore($this->id)
                    ->whereNull('deleted_at')
            ],

            'description' => ['sometimes', 'string', 'min:10'],
            'base_price'  => ['sometimes', 'numeric', 'min:0'],
            'image'       => ['sometimes', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
