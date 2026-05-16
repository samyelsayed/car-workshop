<?php

namespace App\Exceptions\Order;

 use App\Exceptions\BaseException;

class OrderNotOwnedByUserException extends BaseException
{
    protected $code = 403;

    public function __construct()
    {
        parent::__construct('errors.order_not_owned');
    }
}