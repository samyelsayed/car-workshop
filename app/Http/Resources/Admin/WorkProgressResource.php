<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class WorkProgressResource extends JsonResource
{
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

    /**
     * حساب المدة المستغرقة بين البداية والنهاية
     */
    private function calculateDuration(): ?string
    {
        if ($this->started_at && $this->completed_at) {
            $start = Carbon::parse($this->started_at);
            $end = Carbon::parse($this->completed_at);

            // يرجع الفرق بصيغة مقروءة مثل (2 hours, 30 minutes)
            return $start->diffForHumans($end, true);
        }

        return null;
    }
}
