<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
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
            'id' => $this->id,
            'order_id' => $this->order_id,
            'stage' => $this->stage,
            'status' => $this->status,
            'notes' => $this->notes,

            // تنسيق التواريخ
            'started_at' => $this->started_at ? Carbon::parse($this->started_at)->format('Y-m-d H:i') : null,
            'completed_at' => $this->completed_at ? Carbon::parse($this->completed_at)->format('Y-m-d H:i') : null,

            // حقل إضافي "محسوب" يعرض مدة التنفيذ بشكل مقروء
            'duration' => $this->calculateDuration(),

            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
