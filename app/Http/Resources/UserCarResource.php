<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCarResource extends JsonResource
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

        // هنا اللعبة: نبعت التاريخ بتنسيق يسهل على الـ Front-end عرضه
        'added_at'     => $this->created_at->format('Y-m-d H:i'),

     
    ];
    }
}
