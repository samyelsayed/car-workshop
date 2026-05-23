<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class InvalidCredentialsException extends BaseException
{
    // الـ HTTP Status Code لحالة بيانات الدخول غير صحيحة
    protected $code = 401;

    public function __construct()
    {
        // مفتاح الترجمة اللي هنحطه في ملف الـ messages
        parent::__construct('errors.invalid_credentials');
    }
}
