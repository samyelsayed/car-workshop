<?php

namespace App\Http\Resources\Api\Admin\Users;

use App\Http\Resources\Api\User\Addresses\UserAddressResource;
use App\Http\Resources\Api\User\Cars\UserCarResource;
use App\Http\Resources\Api\User\Phones\UserMobileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 1. البيانات التعريفية الأساسية
            'id'                => $this->id,
            'firstName'        => $this->first_name,
            'lastName'         => $this->last_name,
            'fullName'         => $this->first_name . ' ' . $this->last_name,
            'email'             => $this->email,
            'role'              => $this->role,

            // 2. بيانات التواصل (الأساسي والإضافي)
            'allPhones'        => UserMobileResource::collection($this->whenLoaded('user_mobiles')),
            // 5. عربيات اليوزر (الجديد ✨)
            'cars'              => UserCarResource::collection($this->whenLoaded('cars')),

            // 6. عناوين اليوزر (الجديد ✨)
            'addresses'         => UserAddressResource::collection($this->whenLoaded('addresses')),

            // 3. حالات الحساب (Status Flags)
            'status'            => [
                'isVerified'   => $this->email_verified_at !== null,
                'isDeleted'    => $this->deleted_at !== null,
                'isAdmin'      => $this->role === 'admin',
            ],

            // 4. التواريخ التفصيلية (للتقارير)
            'dates'             => [
                'registeredAt' => $this->created_at?->format('Y-m-d H:i'),
                'verifiedAt'   => $this->email_verified_at?->format('Y-m-d H:i'),
                'lastUpdate'   => $this->updated_at?->format('Y-m-d H:i'),
                'deletedAt'    => $this->deleted_at?->format('Y-m-d H:i'), // بيظهر بس لو اليوزر محذوف
            ],

        ];
    }
}
