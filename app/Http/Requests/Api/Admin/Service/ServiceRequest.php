<?php

namespace App\Http\Requests\Api\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
        'name'        => 'required|string|min:3|max:50|unique:services,name',
        'description' => 'required|string|min:10',
        'base_price'  => 'required|numeric|min:0',
        'image'       => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        'is_active'   => 'nullable|boolean',
    ];
    }
}
