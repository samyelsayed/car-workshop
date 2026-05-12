<?php

namespace App\Exceptions\Car;

use App\Exceptions\BaseException;

class CarNotOwnedByUserException extends BaseException
{
    protected $message = 'Access Denied. This vehicle does not belong to your account.';
    protected $code = 403; // Forbidden
}
