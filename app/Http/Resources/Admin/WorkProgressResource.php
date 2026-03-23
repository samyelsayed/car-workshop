<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkProgressResource extends JsonResource
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
            'stage'        => $this->stage,      // اسم المرحلة (فك، تركيب، الخ)
            'status'       => $this->status,     // حالة المرحلة (done, pending)
            'notes'        => $this->notes,
            'started_at'   => $this->started_at,
            'completed_at' => $this->completed_at,
        ];
    }
}
