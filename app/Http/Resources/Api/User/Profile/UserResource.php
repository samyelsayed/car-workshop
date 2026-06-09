<?php

namespace App\Http\Resources\Api\User\Profile;

use App\Http\Resources\Api\User\Cars\UserCarResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Traits\HandlesImageUpload; // 👈 استدعاء التريت هنا

class UserResource extends JsonResource
{
    use HandlesImageUpload;
    public function toArray(Request $request): array
    {
        return [
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'token' => $this->token ?? null,
            'role' => $this->role,
            // 🎯 هنا الروقان! التريت بياخد المسار الخام ويحوله لـ URL كامل. 
            // ولو اليوزر معندوش صورة (null)، بيطلع صورة الديفولت أوتوماتيك.
            'image'     => $this->getImageUrl($this->resource->image, 'images/users/default-user.png'),
            ];
            
    }
}
