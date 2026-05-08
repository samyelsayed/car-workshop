<?php

namespace App\Http\Requests\Api\Admin\Order;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    use MapsCamelCase;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])
            ],
        ];
    }
}
