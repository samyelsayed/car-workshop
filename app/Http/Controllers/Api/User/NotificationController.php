<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\NotificationResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get current user's notifications
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationService->getUserNotifications(
            $request->user()
        );

        return $this->Data(
            ['notifications' => NotificationResource::collection($notifications)],
            __('messages.notifications_retrieved')
        );
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount($request->user());

        return $this->Data(
            ['unread_count' => $count],
            __('messages.unread_count_retrieved')
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $this->notificationService->markAsRead($request->user(), $id);

        return $this->successMessage(__('messages.notification_marked_as_read'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllAsRead($request->user());

        return $this->successMessage(__('messages.all_notifications_marked_as_read'));
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->notificationService->deleteUserNotification($request->user(), $id);

        return $this->successMessage(__('messages.notification_deleted'));
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead(Request $request): JsonResponse
    {
        $count = $this->notificationService->deleteAllRead($request->user());

        return $this->Data(
            ['deleted_count' => $count],
            __('messages.all_read_notifications_deleted', ['count' => $count])
        );
    }
}