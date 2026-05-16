<?php

namespace App\Exceptions\Car;

use App\Exceptions\BaseException;

class CarNotOwnedByUserException extends BaseException
{
    protected $code = 403;

    public function __construct()
    {
        parent::__construct('errors.car_not_owned');
    }
}