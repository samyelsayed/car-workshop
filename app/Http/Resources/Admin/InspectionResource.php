<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'               => $this->id,
            'order_id'         => $this->order_id,
            'type'             => $this->type, // مثلاً: فحص مبدئي، فحص شامل
            'inspection_date'  => $this->inspection_date, // لارايفل هيرجعه string لو مش عامل cast
            'findings'         => $this->findings, // النتائج اللي لقاها الفني
            'estimated_cost'   => (float) $this->estimated_cost, // تحويل لـ float عشان الحسابات في الفرونت
            'notes'            => $this->notes,
            'created_at'       => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
