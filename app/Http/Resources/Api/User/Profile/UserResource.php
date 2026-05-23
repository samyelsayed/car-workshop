<?php

namespace App\Http\Resources\Api\User\Profile;

use App\Http\Resources\Api\User\Cars\UserCarResource;

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
            'token' => $this->token ?? null,
            'role' => $this->role,
        ];
    }
}
