<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Notification\NotificationRequest;
use App\Http\Resources\Admin\NotificationResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiTrait;

    public function __construct(protected NotificationService $notificationService) {}

    /**
     * للأدمن: إرسال إشعار لمستخدم واحد محدد
     */
    public function sendToUser(NotificationRequest $request)
    {
        $notification = $this->notificationService->sendNotificationToUser($request->validated());
        
        return $this->Data(new NotificationResource($notification), 'Notification sent to user successfully', 201);
    }

    /**
     * للأدمن: إرسال إشعار جماعي (Broadcast) لكل المستخدمين أو لدور معين
     */
    public function broadcast(NotificationRequest $request)
    {
        $this->notificationService->broadcastNotification($request->validated());
        
        return $this->Success('Broadcast notification initiated successfully');
    }

    /**
     * للمستخدم: جلب قائمة إشعاراته الشخصية فقط
     */
    public function myNotifications(Request $request)
    {
        $filters = ['user_id' => auth()->id()];
        $notifications = $this->notificationService->getAllNotifications($filters);
        
        return $this->Data(
            NotificationResource::collection($notifications)->response()->getData(true),
            'Your notifications retrieved successfully'
        );
    }

    /**
     * للمستخدم: تحديد إشعار معين كـ "تمت القراءة"
     */
    public function markAsRead(int $id)
    {
        $this->notificationService->markAsRead($id);
        
        return $this->Success('Notification marked as read');
    }

    /**
     * للأدمن: مسح إشعار من السجل
     */
    public function destroy(int $id)
    {
        $this->notificationService->deleteNotification($id);
        
        return $this->Success('Notification deleted successfully');
    }
}