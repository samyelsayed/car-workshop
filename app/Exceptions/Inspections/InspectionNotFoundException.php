<?php

namespace App\Exceptions\Inspections;

use App\Exceptions\BaseException;

class InspectionNotFoundException extends BaseException
{
    protected $message = 'Sorry, no data was found to check this request.';
    protected $code = 404;
}
