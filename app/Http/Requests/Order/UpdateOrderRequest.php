<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool { 
        return true; 
        }

    protected function prepareForValidation()
    {
        // بنعمل merge فقط لو الحقل مبعوث فعلياً (عشان الـ Update جزئي)
        $map = [
            'carId'          => 'car_id',
            'pickupRequired' => 'pickup_required',
            'pickupLocation' => 'pickup_location',
            'pickupDatetime' => 'pickup_datetime',
        ];

        foreach ($map as $camel => $snake) {
            if ($this->has($camel)) {
                $this->merge([$snake => $this->{$camel}]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'car_id'          => ['sometimes', 'required', Rule::exists('cars', 'id')->where('user_id', auth()->id())->whereNull('deleted_at')],
            'services'        => ['sometimes', 'required', 'array', 'min:1'],
            'services.*'      => ['sometimes', 'required', Rule::exists('services', 'id')->where('is_active', 1)],
            'pickup_required' => ['sometimes', 'required', 'boolean'],
            'pickup_location' => ['required_if:pickup_required,true', 'nullable', 'string', 'max:255'],
            'pickup_datetime' => ['required_if:pickup_required,true', 'nullable', 'date', 'after:now'],
        ];
    }
}