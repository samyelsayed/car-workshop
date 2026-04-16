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

public function toArray(Request $request): array
{
    return [
        'id'        => $this->id,
        'firstName' => $this->first_name,
        'lastName'  => $this->last_name,
        'fullName'  => $this->first_name . ' ' . $this->last_name,
        'email'     => $this->email,
        'phone'     => $this->phone, // مهم جداً لمشروع الورشة
        'role'      => $this->role,

        // التوكن يظهر فقط في اللوجن/ريجيستر
        'token'     => $this->when(isset($this->token), $this->token),

        // لو بنعرض اليوزر في صفحة الـ Admin، ممكن نحتاج الـ Cars بتاعته
        'cars'      => UserResource::collection($this->whenLoaded('cars')),

        'joinedAt'  => $this->created_at->format('Y-m-d'),
    ];
}
    }

