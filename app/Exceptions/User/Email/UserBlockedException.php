<?php

namespace App\Exceptions\User\Email;
use App\Exceptions\BaseException;

class UserBlockedException extends BaseException
{
    protected $code = 403;

    public function __construct()
    {
        parent::__construct('errors.user_blocked');
    }
}
