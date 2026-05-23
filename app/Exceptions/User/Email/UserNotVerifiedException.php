<?php

namespace App\Exceptions\User\Email;
use App\Exceptions\BaseException;

class UserNotVerifiedException extends BaseException
{
    protected $code = 403;

    public function __construct()
    {
        parent::__construct('errors.user_not_verified');
    }
}
