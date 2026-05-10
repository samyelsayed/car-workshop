<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    /**
     * Get user notifications (latest 50 without pagination)
     */
    public function getUserNotifications(User $user, int $limit = 50): Collection
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(User $user): int
    {
        return $user->notifications()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(User $user, int $notificationId): void
    {
        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->update(['is_read' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Delete user notification
     */
    public function deleteUserNotification(User $user, int $notificationId): void
    {
        $notification = $user->notifications()->findOrFail($notificationId);

        $notification->delete();
    }
}
