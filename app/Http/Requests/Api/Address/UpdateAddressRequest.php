<?php

namespace App\Http\Requests\Api\Address;

use App\Http\Traits\MapsCamelCase; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'addressType' => 'address_type',
        'isDefault'   => 'is_default',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_type' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['home', 'work', 'other'])
            ],

            'street'  => ['sometimes', 'required', 'string', 'max:255'],
            'city'    => ['sometimes', 'required', 'string', 'max:255'],
            'country' => ['sometimes', 'required', 'string', 'max:255'],

            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}
