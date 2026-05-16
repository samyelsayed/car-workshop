<?php

namespace App\Exceptions\Notification;

use App\Exceptions\BaseException;

class NotificationNotOwnedByUserException extends BaseException
{
    protected $code = 403;

    public function __construct()
    {
        parent::__construct('errors.notification_not_owned');
    }
}