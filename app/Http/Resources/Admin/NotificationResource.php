<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            
            // بيانات المستخدم (لو محملين العلاقة)
            'user_name' => $this->whenLoaded('user', function() {
                return $this->user->first_name . ' ' . $this->user->last_name;
            }),

            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'is_read' => (bool) $this->is_read,

            // التواريخ بتنسيقات مختلفة
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            
            // "منذ دقيقتين" - دي أهم حتة للـ UX في الإشعارات
            'created_at_human' => $this->created_at->diffForHumans(),
        ];
    }
}