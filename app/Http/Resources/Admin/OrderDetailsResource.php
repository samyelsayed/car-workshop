<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            // 1. البيانات الأساسية للأوردر (من جدول orders)
            'id'                => $this->id,
            'status'            => $this->status,
            'total_cost'        => (float) $this->total_cost,
            'pickup_required'   => (bool) $this->pickup_required,
            'pickup_location'   => $this->pickup_location,
            'pickup_datetime'   => $this->pickup_datetime,
            'created_at'        => $this->created_at?->format('Y-m-d H:i'),

            // 2. بيانات العميل (علاقة user)
            'customer'          => new UserResource($this->whenLoaded('user')),

            // 3. بيانات السيارة (علاقة car)
            'car_details'       => new UserCarResource($this->whenLoaded('car')),

            // 4. الخدمات المطلوبة (علاقة orderItems)
            // دي بتستخدم OrderItemResource اللي بيرجع اسم الخدمة وسعرها
            'items'             => OrderItemResource::collection($this->whenLoaded('orderItems')),

            // 5. تقارير الفحص (علاقة inspections)
            'inspections'       => InspectionResource::collection($this->whenLoaded('inspections')),

            // 6. مراحل تطور الشغل (علاقة workProgress)
            'work_progress'     => WorkProgressResource::collection($this->whenLoaded('workProgress')),
        ];
    }


    public func
}
