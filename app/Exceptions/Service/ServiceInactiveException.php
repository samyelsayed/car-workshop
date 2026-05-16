<?php

namespace App\Exceptions\Service;

use App\Exceptions\BaseException;

class ServiceInactiveException extends BaseException
{
    protected $code = 400;

    public function __construct()
    {
        parent::__construct('errors.service_inactive');
    }
}