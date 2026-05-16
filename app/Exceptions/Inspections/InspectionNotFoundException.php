<?php

namespace App\Exceptions\Inspections;

use App\Exceptions\BaseException;

class InspectionNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.inspection_not_found');
    }
}