<?php

namespace App\Http\Requests\Api\User\Car;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    use MapsCamelCase;

    protected array $map = [
        'plateNumber' => 'plate_number',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // استلام الأيدي من الراوت (تأكد إن اسم البراميتر في الـ route هو car)
        $carId = $this->route('car');

        return [
            'plate_number' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('cars', 'plate_number')
                    ->ignore($carId)
                    ->whereNull('deleted_at')
            ],
            'brand' => ['sometimes', 'required', 'string', 'max:50'],
            'model' => ['sometimes', 'required', 'string', 'max:50'],
            'year'  => ['sometimes', 'required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function attributes(): array
    {
        return [
            'plateNumber' => 'plate number',
        ];
    }
}
