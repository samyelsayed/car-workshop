<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\OrderResource;
use App\Http\Resources\UserAddressResource;
use App\Http\Resources\UserCarResource;
use App\Http\Resources\UserMobileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identity' => [
                'id'            => $this->id,
                'full_name'     => $this->first_name . ' ' . $this->last_name,
                'email'         => $this->email,
                'role'          => $this->role,
                'is_verified'   => $this->email_verified_at !== null,
            ],

            'contact' => [
                'primary_phone' => $this->phone,
                'additional_numbers' => UserMobileResource::collection($this->whenLoaded('user_mobiles')),
                'addresses'     => UserAddressResource::collection($this->whenLoaded('addresses')),
            ],

            'assets' => [
                'cars_count'    => $this->cars_count,
                'cars_list'     => UserCarResource::collection($this->whenLoaded('cars')),
            ],

            'activity' => [
                'orders_count'  => $this->orders_count,
                'orders_history' => OrderResource::collection($this->whenLoaded('orders')),
            ],

            'system_logs' => [
                'registered_at' => $this->created_at?->format('Y-m-d H:i'),
                'updated_at'    => $this->updated_at?->format('Y-m-d H:i'),
                'is_trashed'    => $this->trashed(), // ميثود جاهزة في SoftDeletes
                'deleted_at'    => $this->when($this->trashed(), $this->deleted_at?->format('Y-m-d H:i')),
            ]
        ];
    }
}
