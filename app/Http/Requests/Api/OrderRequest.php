<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class OrderRequest extends FormRequest
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

            // 'car_id' => 'required|exists:cars,id,user_id,' . auth()->id(),
            'car_id' => ['required',Rule::exists('cars','id')->where('user_id', auth()->id())->whereNull('deleted_at')],
            'services' => 'required|array|min:1',
            'services.*' =>  ['required',Rule::exists('services', 'id')->where('is_active', 1)],
            'pickup_required' => 'required|boolean',
            'pickup_location' => 'required_if:pickup,true|string|max:255',
            'pickup_datetime' => 'required_if:pickup,true|date|after:now'

        ];
    }
}
