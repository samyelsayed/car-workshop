<?php

namespace App\Http\Controllers\Api\Admin\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Notification\BroadcastNotificationRequest;
use App\Http\Requests\Api\Admin\Notification\SendNotificationRequest;
use App\Http\Resources\Api\Admin\Notifications\NotificationResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiTrait;

    public function __construct(protected NotificationService $notificationService) {}

    /**
     * للأدمن: جلب كل الإشعارات في السيستم مع الفلترة والبحث
     */
public function index(Request $request)
{
    // لقط البيانات بـ camelCase وتحويلها لـ snake_case للسيرفس 🎯
    $filters = [
        'user_id' => $request->query('userId'),
        'type'    => $request->query('type'),
        'is_read' => $request->query('isRead'),
        'search'  => $request->query('search'),
    ];

    $notifications = $this->notificationService->getAllNotifications($filters);

    return $this->Data(
        NotificationResource::collection($notifications)->response()->getData(true),
        __('messages.notifications_retrieved')
    );
}

    /**
     * للأدمن: إرسال إشعار لمستخدم واحد محدد
     */
    public function sendToUser(SendNotificationRequest $request)
    {
        $notification = $this->notificationService->sendNotificationToUser($request->validated());

        return $this->Data(new NotificationResource($notification), __('messages.notification_sent'), 201);
    }

    /**
     * للأدمن: إرسال إشعار جماعي (Broadcast) لكل المستخدمين أو لدور معين
     */
    public function broadcast(BroadcastNotificationRequest $request)
    {
        $this->notificationService->broadcastNotification($request->validated());

        return $this->SuccessMessage(__('messages.notification_broadcast_initiated'));
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
            __('messages.notifications_retrieved')
        );
    }

    /**
     * للمستخدم: تحديد إشعار معين كـ "تمت القراءة"
     */
    public function markAsRead(int $id)
    {
        $this->notificationService->markAsRead($id);

        return $this->SuccessMessage(__('messages.notification_marked_as_read'));
    }

    /**
     * للأدمن: مسح إشعار من السجل
     */
    public function destroy(int $id)
    {
        $this->notificationService->deleteNotification($id);

        return $this->SuccessMessage(__('messages.notification_deleted'));
    }
}
