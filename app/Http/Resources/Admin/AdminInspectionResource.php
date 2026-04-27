<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminInspectionResource extends JsonResource
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
            'order_id' => $this->order_id,
            'type' => $this->type,
            'inspection_date' => $this->inspection_date,
            'findings' => $this->findings,
            'estimated_cost' => number_format($this->estimated_cost, 2),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i'),


        ];
    }
}
