<?php

namespace App\Http\Requests\Order;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    use MapsCamelCase;

    // الخريطة لتحويل كل حقول الـ Pickup والـ Car
    protected array $map = [
        'carId'          => 'car_id',
        'pickupRequired' => 'pickup_required',
        'pickupLocation' => 'pickup_location',
        'pickupDatetime' => 'pickup_datetime',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // التأكد إن العربية ملك لليوزر ومش ممسوحة (Soft Delete)
            'car_id' => [
                'required',
                Rule::exists('cars', 'id')
                    ->where('user_id', auth()->id())
                    ->whereNull('deleted_at')
            ],

            // التأكد إن الخدمات المختارة موجودة ونشطة
            'services'   => ['required', 'array', 'min:1'],
            'services.*' => [
                'required',
                Rule::exists('services', 'id')->where('is_active', 1)
            ],

            'pickup_required' => ['required', 'boolean'],

            // شروط مرتبطة لو طلب خدمة الاستلام (Pickup)
            'pickup_location' => [
                'required_if:pickup_required,true',
                'nullable',
                'string',
                'max:255'
            ],

            'pickup_datetime' => [
                'required_if:pickup_required,true',
                'nullable',
                'date',
                'after:now'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'carId'          => 'car',
            'pickupRequired' => 'pickup service',
            'pickupLocation' => 'pickup location',
            'pickupDatetime' => 'pickup time',
            'services'       => 'selected services',
        ];
    }
}
