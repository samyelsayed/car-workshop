<?php

namespace App\Exceptions\WorkProgress;

use App\Exceptions\BaseException;

class WorkProgressNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.work_progress_not_found');
    }
}