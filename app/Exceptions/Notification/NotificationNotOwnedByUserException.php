<?php

namespace App\Exceptions\Notification;

use App\Exceptions\BaseException;

class NotificationNotOwnedByUserException extends BaseException
{
    protected $message = 'Access Denied. You do not have permission to manage this notification.';
    protected $code = 403; // Forbidden
}