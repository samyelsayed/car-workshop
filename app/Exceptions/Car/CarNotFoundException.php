<?php

namespace App\Exceptions\Car;

use App\Exceptions\BaseException;

class CarNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.car_not_found');
    }
}