<?php

namespace App\Http\Resources\Api\Admin\Users;

use App\Http\Resources\Api\User\Orders\OrderResource;
use App\Http\Resources\Api\User\Addresses\UserAddressResource;
use App\Http\Resources\Api\User\Cars\UserCarResource;
use App\Http\Resources\Api\User\Phones\UserMobileResource;
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
                'fullName'     => $this->first_name . ' ' . $this->last_name,
                'email'         => $this->email,
                'role'          => $this->role,
                'isVerified'   => $this->email_verified_at !== null,
            ],

            'contact' => [
                'primaryPhone' => $this->phone,
                'additionalNumbers' => UserMobileResource::collection($this->whenLoaded('user_mobiles')),
                'addresses'     => UserAddressResource::collection($this->whenLoaded('addresses')),
            ],

            'assets' => [
                'carsCount'    => $this->cars_count,
                'carsList'     => UserCarResource::collection($this->whenLoaded('cars')),
            ],

            'activity' => [
                'ordersCount'  => $this->orders_count,
                'ordersHistory' => OrderResource::collection($this->whenLoaded('orders')),
            ],

            'systemLogs' => [
                'registeredAt' => $this->created_at?->format('Y-m-d H:i'),
                'updatedAt'    => $this->updated_at?->format('Y-m-d H:i'),
                'isTrashed'    => $this->trashed(), // ميثود جاهزة في SoftDeletes
                'deletedAt'    => $this->when($this->trashed(), $this->deleted_at?->format('Y-m-d H:i')),
            ]
        ];
    }
}
