<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
return [
            'id' => $this->id,
            'mobile_number' => $this->mobile_number,
            // 'user_id' => $this->user_id, // اختياري لو محتاجه
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
