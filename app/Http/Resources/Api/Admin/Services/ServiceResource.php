<?php

namespace App\Http\Resources\Api\Admin\Services;

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
        'serviceName'  => $this->name,
        'details'       => $this->description,
        'price'         => $this->base_price,
        'imageUrl'     =>asset('images/services/' . $this->image),
        'isActive'     =>(bool)$this->is_active,
        'date'          =>$this->created_at->format('Y-m-d')
        ];
    }
}
