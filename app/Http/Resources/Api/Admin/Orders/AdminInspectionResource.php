<?php

namespace App\Http\Resources\Api\Admin\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminInspectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'orderId'        => $this->order_id,          // تحويل لـ camelCase 🎯
            'type'           => $this->type,              // جاهزة أصلًا
            'inspectionDate' => $this->inspection_date,   // تحويل لـ camelCase
            'findings'       => $this->findings,          // جاهزة أصلًا
            'estimatedCost'  => number_format($this->estimated_cost, 2), // تنسيق الرقم مع الـ camelCase
            'notes'          => $this->notes,             // جاهزة أصلًا
            'createdAt'      => $this->created_at?->format('Y-m-d H:i'), // تحويل لـ camelCase وحمايتها بـ ?->
        ];
    }
}
