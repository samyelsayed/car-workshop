<?php

namespace App\Exceptions\Car;

use App\Exceptions\BaseException;

class CarNotFoundException extends BaseException
{
    protected $message = 'The car you are looking for is not registered in our workshop.';
    protected $code = 404; // Not Found
}
