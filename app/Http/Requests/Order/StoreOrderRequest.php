<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool { 
        return true;
        
         }

    protected function prepareForValidation()
    {
        $this->merge([
            'car_id'          => $this->carId,
            'pickup_required' => $this->pickupRequired,
            'pickup_location' => $this->pickupLocation,
            'pickup_datetime' => $this->pickupDatetime,
        ]);
    }

    public function rules(): array
    {
        return [
            'car_id'          => ['required', Rule::exists('cars', 'id')->where('user_id', auth()->id())->whereNull('deleted_at')],
            'services'        => ['required', 'array', 'min:1'],
            'services.*'      => ['required', Rule::exists('services', 'id')->where('is_active', 1)],
            'pickup_required' => ['required', 'boolean'],
            'pickup_location' => ['required_if:pickup_required,true', 'nullable', 'string', 'max:255'],
            'pickup_datetime' => ['required_if:pickup_required,true', 'nullable', 'date', 'after:now'],
        ];
    }
}