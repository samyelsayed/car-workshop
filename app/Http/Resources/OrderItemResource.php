<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id'            => $this->id,
        'service_id'    => $this->service_id,
        'service_name'  => $this->service_name,   // Snapshot
        'service_image' => $this->service_image,  // Snapshot
        'unit_price'    => (float) $this->unit_price, // Snapshot
        'quantity'      => $this->quantity,
        'subtotal'      => (float) $this->subtotal,
    ];
    }
}
