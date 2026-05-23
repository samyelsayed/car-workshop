<?php

namespace App\Http\Resources\Api\User\Profile;

use App\Http\Resources\Api\User\Cars\UserCarResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
