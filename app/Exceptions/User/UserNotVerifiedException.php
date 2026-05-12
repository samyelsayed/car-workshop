<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseException;

class UserNotVerifiedException extends BaseException
{
    protected $message = 'Please verify your account to access this service.';
    protected $code = 403; // Forbidden
}
