<?php

namespace App\Http\Requests\Api\Address;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest
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
            'address_type' => ['required', 'string', Rule::in(['home', 'work', 'other'])],

            'street'    => ['required', 'string', 'max:255'],
            'city'      => ['required', 'string', 'max:255'],
            'country'   => ['required', 'string', 'max:255'],

            'is_default' => ['sometimes', 'boolean'],
        ];
    }

}
