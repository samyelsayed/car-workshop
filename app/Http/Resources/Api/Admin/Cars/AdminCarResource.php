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
            'id'           => $this->id,
            'plate_number' => $this->plate_number,
            'brand'        => $this->brand,
            'model'        => $this->model,
            'year'         => $this->year,
            'color'        => $this->color,
            
            // بيجيب بيانات صاحب العربية لو معملولها Eager Loading في السيرفيس
            'customer'     => new UserResource($this->whenLoaded('user')),
            
            'added_at'     => $this->created_at->format('Y-m-d H:i'),
            
            // عشان الأدمن يشوف لو العربية دي ممسوحة (Soft Deleted) ولا لأ
            'deleted_at'   => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i') : null,
        ];
    }
}