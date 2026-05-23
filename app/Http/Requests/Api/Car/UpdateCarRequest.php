<?php

namespace App\Http\Requests\Api\Car;

use App\Exceptions\Car\CarNotOwnedByUserException;
use App\Http\Traits\MapsCamelCase;
use App\Models\Car;
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
        $carId = $this->route('id') ?? $this->route('car');

        if (!$carId) {
            return false;
        }


            // 1. نشيك هل العربية مملوكة للمستخدم الحالي أم لا
        $isOwned = Car::where('id', $carId)
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->exists();

        // 2. لو مش مملوكة لليوزر، بنرمي الـ Custom Exception فوراً قبل ما لارايفل تقفل الريكويست
        if (!$isOwned) {
            throw new CarNotOwnedByUserException();
        }

        return true;
    }

    public function rules(): array
    {
        // استلام الأيدي من الراوت (تأكد إن اسم البراميتر في الـ route هو id)
        $carId = $this->route('id') ?? $this->route('car');

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
