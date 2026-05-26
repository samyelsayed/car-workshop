<?php
namespace App\Http\Traits;

trait MapsCamelCase
{
    protected function mapCamelCaseInputs(): void
    {
        // 1. التأكد من وجود مصفوفة الخريطة (map) في الـ Request
        if (!property_exists($this, 'map')) {
            return;
        }

        $toMerge = [];

        foreach ($this->map as $camel => $snake) {
            // 2. استخدام filled للتأكد من وجود قيمة حقيقية (ليست null أو فارغة)
            // 3. التحقق من عدم إرسال القيمة بصيغة snake_case مسبقاً (Fallback)
            if ($this->filled($camel) && !$this->has($snake)) {
                $toMerge[$snake] = $this->input($camel);
            }
        }

        // 4. دمج البيانات الجديدة في الـ Request فقط إذا وُجدت
        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }

    /**
     * تحويل البيانات من camelCase إلى snake_case قبل عملية الـ Validation
     */
    protected function prepareForValidation(): void
    {
        $this->mapCamelCaseInputs();
    }
}
