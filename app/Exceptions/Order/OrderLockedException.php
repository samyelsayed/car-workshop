<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderLockedException extends BaseException
{
    protected $message = 'This order is completed or cancelled and cannot be modified.';
    protected $code = 422;
}
