<?php

namespace App\Exceptions\Notification;

use App\Exceptions\BaseException;

class NotificationNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.notification_not_found');
    }
}