<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.order_not_found');
    }
}