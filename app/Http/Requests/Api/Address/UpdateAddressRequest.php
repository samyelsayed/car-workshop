<?php

namespace App\Http\Requests\Api\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_type' => 'required|string|in:home,work,other',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'is_default' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $mapped = [];

        if ($this->has('addressType')) {
            $mapped['address_type'] = $this->input('addressType');
        }

        if ($this->has('isDefault')) {
            $mapped['is_default'] = $this->input('isDefault');
        }

        $this->merge($mapped);
    }
}
