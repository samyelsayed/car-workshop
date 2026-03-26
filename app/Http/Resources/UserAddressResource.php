<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'address_type' => $this->address_type, // (Home, Work, etc.)
            'full_address' => "{$this->street}, {$this->city}, {$this->country}", // دمج العنوان في حقل واحد للسهولة
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
            'is_default' => (bool) $this->is_default, // تحويلها لـ boolean حقيقي
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
