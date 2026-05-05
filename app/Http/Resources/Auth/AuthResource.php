<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * تحويل البيانات لشكل منظم وموحد
     */
    public function toArray(Request $request): array
    {
        return [
            // بناخد الإيميل من أوبجكت الـ user اللي راجع من السيرفس
            'email' => $this['user']->email,
            // والتوكن اللي اتعمله generate
            'access_token' => $this['token'],
            'token_type'   => 'Bearer',
        ];
    }
}