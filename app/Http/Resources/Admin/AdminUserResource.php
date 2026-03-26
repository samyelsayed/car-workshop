<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\UserMobileResource;
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
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'full_name'         => $this->first_name . ' ' . $this->last_name,
            'email'             => $this->email,
            'role'              => $this->role,

            // 2. بيانات التواصل (الأساسي والإضافي)
            'primary_phone'     => $this->phone,
            'all_phones'        => UserMobileResource::collection($this->whenLoaded('user_mobiles')),

            // 3. حالات الحساب (Status Flags)
            'status'            => [
                'is_verified'   => $this->email_verified_at !== null,
                'is_deleted'    => $this->deleted_at !== null,
                'is_admin'      => $this->role === 'admin',
            ],

            // 4. التواريخ التفصيلية (للتقارير)
            'dates'             => [
                'registered_at' => $this->created_at?->format('Y-m-d H:i'),
                'verified_at'   => $this->email_verified_at?->format('Y-m-d H:i'),
                'last_update'   => $this->updated_at?->format('Y-m-d H:i'),
                'deleted_at'    => $this->deleted_at?->format('Y-m-d H:i'), // بيظهر بس لو اليوزر محذوف
            ],

        ];
    }
}
