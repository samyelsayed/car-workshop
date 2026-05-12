<?php

namespace App\Exceptions\Service;

use App\Exceptions\BaseException;

class ServiceNotFoundException extends BaseException
{
    protected $message = 'The requested service could not be found.';
    protected $code = 404;
}
