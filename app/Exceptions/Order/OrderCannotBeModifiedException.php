<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderCannotBeModifiedException extends BaseException
{
    protected $code = 422;

    public function __construct()
    {
        parent::__construct('errors.order_cannot_be_modified');
    }
}