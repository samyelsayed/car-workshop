<?php

namespace App\Http\Resources\Api\Admin\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class WorkProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
      return [
        'id'          => $this->id,
        'orderId'     => $this->order_id,      // تحويل لـ camelCase ✨
        'stage'       => $this->stage,
        'status'      => $this->status,
        'notes'       => $this->notes,
        'startedAt'   => $this->started_at ? $this->started_at->format('Y-m-d H:i') : null,   // تحويل لـ camelCase ✨
        'completedAt' => $this->completed_at ? $this->completed_at->format('Y-m-d H:i') : null, // تحويل لـ camelCase ✨
        'duration'    => $this->duration,
        'createdAt'   => $this->created_at->format('Y-m-d H:i'), // تحويل لـ camelCase ✨
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
