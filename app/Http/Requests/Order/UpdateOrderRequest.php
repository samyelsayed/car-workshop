<?php

namespace App\Http\Requests\Order;

use App\Http\Traits\MapsCamelCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    use MapsCamelCase;

    // الخريطة ثابتة والترايت هيتعامل مع الـ sometimes تلقائياً
    protected array $map = [
        'carId'          => 'car_id',
        'pickupRequired' => 'pickup_required',
        'pickupLocation' => 'pickup_location',
        'pickupDatetime' => 'pickup_datetime',
    ];

    public function authorize(): bool
    {
        $orderId = $this->route('id') ?? $this->route('order');

        if (!$orderId) {
            return true;
        }

        $order = auth()->user()
            ->orders()
            ->where('id', $orderId)
            ->first();

    if (!$order) {
        // 🔥 ارمي الـ الـ Exception بتاعك هنا فوراً وهيشتغل بالملي!
        throw new \App\Exceptions\Order\OrderNotOwnedByUserException();
    }

    return true; // كدة تمام واليوزر يملك الأوردر
    }

    public function rules(): array
    {
        return [
            // استخدام sometimes يضمن إن الـ Validation يشتغل فقط لو الحقل مبعوث
            'car_id' => [
                'sometimes',
                'required',
                Rule::exists('cars', 'id')
                    ->where('user_id', auth()->id())
                    ->whereNull('deleted_at')
            ],

            'services'   => ['sometimes', 'required', 'array', 'min:1'],
            'services.*' => [
                'sometimes',
                'required',
                Rule::exists('services', 'id')->where('is_active', 1)
            ],

            'pickup_required' => ['sometimes', 'required', 'boolean'],

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
