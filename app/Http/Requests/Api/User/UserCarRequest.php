<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCarRequest extends FormRequest
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

   $id = $this->route('id');

        return [
            'plate_number' => ['sometimes','required','string','max:20',Rule::unique('cars', 'plate_number')->ignore($id)->whereNull('deleted_at')],
            'brand'        => 'sometimes|required|string|max:50',
            'model'        => 'sometimes|required|string|max:50',
            'year'         => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'color'        => 'nullable|string|max:30',
        ];
    }
}
