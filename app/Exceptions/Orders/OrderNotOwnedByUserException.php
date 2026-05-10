<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderNotOwnedByUserException extends BaseException
{
    protected $message = 'You do not have permission to access or modify this order.';
    protected $code = 403; // Forbidden
}