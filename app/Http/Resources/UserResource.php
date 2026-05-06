<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}

// UserDetailedResource (تفصيلي - للـ Admin)
class UserDetailedResource extends UserResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'phone' => $this->phone,
            'joinedAt' => $this->created_at->format('Y-m-d'),
            'cars' => UserCarResource::collection($this->whenLoaded('cars')),
        ]);
    }
}
