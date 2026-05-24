<?php

namespace App\Http\Resources\Api\User\Orders;

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
        'id'           => $this->id,
        'serviceId'    => $this->service_id,
        'serviceName'  => $this->service_name,
        'serviceImage' => $this->service_image,
        'unitPrice'    => (float) $this->unit_price,
        'quantity'     => (int) $this->quantity,
        'subtotal'     => (float) $this->subtotal,
    ];
    }
}
