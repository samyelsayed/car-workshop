<?php

namespace App\Http\Requests\Api\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
        'id'          => 'required|exists:services,id',
        'name'        => ['sometimes','string','max:20',Rule::unique('services', 'name')->ignore($this->id)->whereNull('deleted_at')],
        'description' => 'sometimes|string|min:10',
        'base_price'  => 'sometimes|numeric|min:0',
        'image'       => 'sometimes|image|mimes:jpg,png,jpeg,webp|max:2048',
        'is_active'   => 'nullable|boolean',
    ];
    }
}
