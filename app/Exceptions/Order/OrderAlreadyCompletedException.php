<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderAlreadyCompletedException extends BaseException
{
    protected $message = 'This order is already completed and cannot be modified.';
    protected $code = 422; // Unprocessable Entity
}
