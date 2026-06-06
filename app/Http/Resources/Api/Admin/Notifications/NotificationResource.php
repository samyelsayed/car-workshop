<?php

namespace App\Http\Resources\Api\Admin\Notifications;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'orderId' => $this->order_id,

            // بيانات المستخدم (لو محملين العلاقة)
            'userName' => $this->whenLoaded('user', function() {
                return $this->user->first_name . ' ' . $this->user->last_name;
            }),

            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'isRead' => (bool) $this->is_read,

            // التواريخ بتنسيقات مختلفة
            'createdAt' => $this->created_at->format('Y-m-d H:i'),

            // "منذ دقيقتين" - دي أهم حتة للـ UX في الإشعارات
            'createdAtHuman' => $this->created_at->diffForHumans(),
        ];
    }
}
