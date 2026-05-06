<?php

namespace App\Http\Requests\Api\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    /**
     * تحديد الصلاحيات - بما إنه ملف تعديل فغالباً مسموح للمستخدم المسجل
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * تعديل الـ Rules لاستخدام sometimes لتسهيل التحديث الجزئي
     */
    public function rules(): array
    {
        return [
            // أضفنا sometimes عشان مش لازم يبعت كل الداتا في التعديل
            'address_type' => 'sometimes|required|string|in:home,work,other',
            'street'       => 'sometimes|required|string|max:255',
            'city'         => 'sometimes|required|string|max:255',
            'country'      => 'sometimes|required|string|max:255',
            'is_default'   => 'sometimes|boolean',
        ];
    }

    /**
     * تحويل الـ camelCase من الـ Frontend لـ snake_case قبل الـ Validation
     */
    protected function prepareForValidation()
    {
        $mapped = [];

        // بنتشيك لو الداتا مبعوتة فعلاً عشان منعملش override لبيانات مبعوتة بالـ snake_case
        if ($this->has('addressType')) {
            $mapped['address_type'] = $this->input('addressType');
        }

        if ($this->has('isDefault')) {
            $mapped['is_default'] = $this->input('isDefault');
        }

        if (!empty($mapped)) {
            $this->merge($mapped);
        }
    }
}
