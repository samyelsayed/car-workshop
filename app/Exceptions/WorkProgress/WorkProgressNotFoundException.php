<?php

namespace App\Exceptions\WorkProgress;

use App\Exceptions\BaseException;

class WorkProgressNotFoundException extends BaseException
{
    protected $message = 'Sorry, the requested work stage was not found.';
    protected $code = 404;
}
