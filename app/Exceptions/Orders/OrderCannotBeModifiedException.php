<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderCannotBeModifiedException extends BaseException
{
    protected $message = 'The current order status does not allow this action.';
    protected $code = 422; // Unprocessable Entity
}