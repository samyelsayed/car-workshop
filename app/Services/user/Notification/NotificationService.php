<?php

namespace App\Services\User\Notification;

use App\Exceptions\Notification\NotificationNotFoundException;
use App\Exceptions\Notification\NotificationNotOwnedByUserException;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    /**
     * Get user notifications (latest 50)
     */
    public function getUserNotifications(User $user, int $limit = 50): Collection
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications count
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
        $notification = $this->findUserNotificationOrFail($notificationId, $user);
        
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
        $notification = $this->findUserNotificationOrFail($notificationId, $user);
        
        $notification->delete();
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead(User $user): int
    {
        return $user->notifications()
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Find user notification or fail
     */
    protected function findUserNotificationOrFail(int $notificationId, User $user): Notification
    {
        // 1. Check if notification exists
        $notification = Notification::find($notificationId);

        if (!$notification) {
            throw new NotificationNotFoundException();
        }

        // 2. Check ownership
        if ($notification->user_id !== $user->id) {
            throw new NotificationNotOwnedByUserException();
        }

        return $notification;
    }
}