<?php

namespace App\Exceptions\Orders;

use App\Exceptions\BaseException;

class OrderLockedException extends BaseException
{
    protected $message = 'This order is completed or cancelled and cannot be modified.';
    protected $code = 422;
}