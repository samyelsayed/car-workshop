<?php

namespace App\Http\Requests\Api\Car;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarRequest extends FormRequest
{
    use MapsCamelCase;

    // المابنج عشان يستقبل plateNumber من الفرونت إند ويحولها لـ plate_number
    protected array $map = [
        'plateNumber' => 'plate_number',
    ];

    /**
     * هل اليوزر مصرح ليه ينفذ الريكويست؟
     */
    public function authorize(): bool
    {
        // طالما يوزر مسجل دخول بالتوكن يقدر يضيف عربية لنفسه عادي
        return auth()->check();
    }

    /**
     * شروط الفاليوم الخاصة بإنشاء عربية جديدة
     */
    public function rules(): array
    {
        return [
            'plate_number' => [
                'required',
                'string',
                'max:20',
                // شيك إن النمرة مش مكررة في العربيات اللي مش ممسوحة soft delete
                Rule::unique('cars', 'plate_number')->whereNull('deleted_at')
            ],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year'  => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * الأسماء المستعارة للإيرورز عشان تظهر بشكل شيك
     */
    public function attributes(): array
    {
        return [
            'plateNumber' => 'plate number',
        ];
    }
}
