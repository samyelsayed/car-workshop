<?php

namespace App\Exceptions\Service;

use App\Exceptions\BaseException;

class ServiceNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.service_not_found');
    }
}