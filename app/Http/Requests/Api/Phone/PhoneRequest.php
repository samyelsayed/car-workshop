<?php

namespace App\Http\Requests\Api\Phone;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PhoneRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'mobileNumber' => 'mobile_number',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile_number' => [
                'required',
                'regex:/^01[0-2,5,9]{1}[0-9]{8}$/',
                Rule::unique('user_mobiles', 'mobile_number')
            ],
        ];
    }
}
