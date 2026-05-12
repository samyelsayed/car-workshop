<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseException;

class UserBlockedException extends BaseException
{
    protected $message = 'Your account has been suspended. Please contact the workshop management.';
    protected $code = 403; // Forbidden
}
