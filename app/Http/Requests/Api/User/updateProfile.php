<?php

namespace App\Http\Requests\Api\User;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    use MapsCamelCase;

    // خريطة التحويل من camelCase لـ snake_case
    protected array $map = [
        'firstName' => 'first_name',
        'lastName'  => 'last_name',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name'  => ['required', 'string', 'min:3', 'max:50'],

            // التحقق من الصورة ومساحتها (2MB كحد أقصى)
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,png,jpeg',
                'max:2048'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'firstName' => 'first name',
            'lastName'  => 'last name',
            'image'     => 'profile picture',
        ];
    }
}
