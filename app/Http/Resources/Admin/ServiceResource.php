<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
        'service_name'  => $this->name,
        'details'       => $this->description,
        'price'         => $this->base_price,
        'image_url'     =>asset('images/services/' . $this->image),
        'is_active'     =>(bool)$this->is_active,
        'date'          =>$this->created_at->format('Y-m-d')
        ];
    }
}
