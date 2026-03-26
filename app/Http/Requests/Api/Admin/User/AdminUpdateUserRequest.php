<?php

namespace App\Http\Requests\Api\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateUserRequest extends FormRequest
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
                'first_name' => ['required', 'string', 'max:50'],
                'last_name'  => ['required', 'string', 'max:50'],
                'email'      => ['required','email',
                    Rule::unique('users')->ignore($this->route('user')) ],
                'role'       => ['required', Rule::in(['admin', 'user'])],
                'phone'      => ['nullable', 'string'], // لو حابب تحدث الموبايل الأساسي هنا
                     ];
    }
}
