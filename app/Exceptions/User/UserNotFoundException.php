<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseException;

class UserNotFoundException extends BaseException
{
    protected $code = 404;

    public function __construct()
    {
        parent::__construct('errors.user_not_found');
    }
}