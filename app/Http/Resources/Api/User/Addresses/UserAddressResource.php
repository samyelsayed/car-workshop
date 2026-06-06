<?php

namespace App\Http\Resources\Api\User\Addresses;

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
            'addressType' => $this->address_type, // (Home, Work, etc.)
            'fullAddress' => "{$this->street}, {$this->city}, {$this->country}", // دمج العنوان في حقل واحد للسهولة
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
            'isDefault' => (bool) $this->is_default, // تحويلها لـ boolean حقيقي
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
