<?php

namespace App\Http\Resources\Api\Admin\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Traits\HandlesImageUpload; // 👈 1. استدعاء التريت

class ServiceResource extends JsonResource
{
    use HandlesImageUpload;
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
        'imageUrl'    => $this->getImageUrl($this->resource->image, 'images/services/default-service.png'),
        'isActive'     =>(bool)$this->is_active,
        'date'          =>$this->created_at->format('Y-m-d')
        ];
    }
}
