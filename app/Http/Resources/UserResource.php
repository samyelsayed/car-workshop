<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id'         => $this->id,
        //     'first_name' => $this->first_name,
        //     'last_name' => $this->last_name,
        //     'full_name' => $this->first_name . ' ' . $this->last_name,
        //     'email'      => $this->email,
        //     'role'       => $this->role,
        //     'token'      => $this->when(isset($this->token), $this->token),
        //     'joined_at'  => $this->created_at->format('Y-m-d'),
        // ];

        return [
            'id'        => $this->id,
            'firstName' => $this->first_name, // حولناها لـ camelCase
            'lastName'  => $this->last_name,  // حولناها لـ camelCase
            'fullName'  => $this->first_name . ' ' . $this->last_name,
            'email'     => $this->email,
            'role'      => $this->role,
            // التوكن هيظهر بس لو موجود (زي في حالة اللوجن أو الريجيستر)
            'token'     => $this->when(isset($this->token), $this->token),
            'joinedAt'  => $this->created_at->format('Y-m-d'),
        ];
    }
}
