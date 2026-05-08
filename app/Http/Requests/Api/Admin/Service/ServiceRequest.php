<?php

namespace App\Http\Requests\Api\Admin\Service;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'name' => [
                'required','string','min:3','max:50',
                Rule::unique('services', 'name')->ignore($this->service)
            ],
            'description' => ['required', 'string', 'min:10'],
            'base_price'  => ['required', 'numeric', 'min:0'],
            'image'       => ['required', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
