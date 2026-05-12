<?php

namespace App\Exceptions\Service;

use App\Exceptions\BaseException;

class ServiceInactiveException extends BaseException
{
    protected $message = 'This service is temporarily unavailable.';
    protected $code = 400; // Bad Request
}
