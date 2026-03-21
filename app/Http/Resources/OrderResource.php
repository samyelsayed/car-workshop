<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id'              => $this->id,
        'user_id'         => $this->user_id,
        'car_id'          => $this->car_id,
        'pickup_location' => $this->pickup_location,
        'pickup_datetime' => $this->pickup_datetime,
        'status'          => $this->status,
        'total_cost'      => (float) $this->total_cost,
        'created_at'      => $this->created_at->format('Y-m-d H:i:s'),

        // عرض بيانات السيارة (لو محملة)
        'car' => $this->whenLoaded('car', function() {
            return [
                'id'           => $this->car->id,
                'brand'        => $this->car->brand,
                'model'        => $this->car->model,
                'plate_number' => $this->car->plate_number,
            ];
        }),

        // عرض الخدمات باستخدام الـ ItemResource اللي عملناه
        'items' => OrderItemResource::collection($this->whenLoaded('items')),
    ];
    }
}
