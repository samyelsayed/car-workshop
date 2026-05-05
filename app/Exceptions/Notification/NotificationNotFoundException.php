<?php

namespace App\Exceptions\Inspections;

use App\Exceptions\BaseException;

class NotificationNotFoundException extends BaseException
{
    protected $message = 'Notification not found or may have been deleted.';
    protected $code = 404;
}
