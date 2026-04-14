 <?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'totalCost'      => (float) $this->total_cost, // تحويل لـ CamelCase
            'createdAt'      => $this->created_at->format('Y-m-d H:i:s'),

            // البيانات اللي الـ Frontend بيحتاجها في الـ Forms (CamelCase)
            'carId'          => $this->car_id,
            'pickupLocation' => $this->pickup_location,
            'pickupDatetime' => $this->pickup_datetime,

            // عرض بيانات السيارة (باستخدام whenLoaded للأمان)
            'car' => $this->whenLoaded('car', function() {
                return [
                    'id'          => $this->car->id,
                    'brand'       => $this->car->brand,
                    'model'       => $this->car->model,
                    'plateNumber' => $this->car->plate_number, // CamelCase برضه
                ];
            }),

            // عرض الخدمات (Snapshots)
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}