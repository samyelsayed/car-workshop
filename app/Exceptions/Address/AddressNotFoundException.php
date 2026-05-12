<?php

namespace App\Exceptions\Address;

use App\Exceptions\BaseException;

class AddressNotFoundException extends BaseException
{
    protected $message = 'The specified address could not be found in our records.';
    protected $code = 404;
}