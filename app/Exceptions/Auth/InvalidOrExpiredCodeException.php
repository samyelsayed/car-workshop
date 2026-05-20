<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class InvalidOrExpiredCodeException extends BaseException
{
    // الـ HTTP Status Code لبيانات غير صالحة
    protected $code = 400; 

    public function __construct()
    {
        // هنمرر مفتاح الترجمة اللي متعود عليه في الـ Localization
        parent::__construct('errors.invalid_or_expired_code');
    }
}