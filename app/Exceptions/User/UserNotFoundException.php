<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseException;

class UserNotFoundException extends BaseException
{
    protected $message = 'The requested user could not be found in our records.';
    protected $code = 404; // Not Found
}
