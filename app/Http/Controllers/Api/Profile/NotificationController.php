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

        return $this->data(
            ['notifications' => NotificationResource::collection($notifications)],
            'Notifications retrieved successfully'
        );
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount($request->user());

        return $this->data(
            ['unread_count' => $count],
            'Unread count retrieved successfully'
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $this->notificationService->markAsRead($request->user(), $id);

        return $this->successMessage('Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllAsRead($request->user());

        return $this->successMessage('All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->notificationService->deleteUserNotification($request->user(), $id);

        return $this->successMessage('Notification deleted successfully');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead(Request $request): JsonResponse
    {
        $count = $this->notificationService->deleteAllRead($request->user());

        return $this->data(
            ['deleted_count' => $count],
            "{$count} notifications deleted successfully"
        );
    }
}