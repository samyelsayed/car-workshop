<?php

namespace App\Http\Resources\Api\Admin\Cars;

use App\Http\Resources\Api\User\Profile\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'plateNumber' => $this->plate_number, // تحويل لـ camelCase ✨
            'brand'       => $this->brand,
            'model'       => $this->model,
            'year'        => $this->year,
            'color'       => $this->color,
            
            // بيانات صاحب العربية (مع استخدام الكوندشن الرايق بتاعك)
            'customer'    => new UserResource($this->whenLoaded('user')),
            
            'addedAt'     => $this->created_at->format('Y-m-d H:i'), // تحويل لـ camelCase ✨
            'deletedAt'   => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i') : null, // تحويل لـ camelCase ✨
        ];
    }
}