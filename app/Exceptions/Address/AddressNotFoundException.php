<?php

namespace App\Exceptions\Address;

use App\Exceptions\BaseException;

class AddressNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.address_not_found');
    }
}