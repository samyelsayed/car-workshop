<?php

namespace App\Exceptions\User\mobile;

use App\Exceptions\BaseException;

class MobileNotFoundException extends BaseException
{
    // الـ HTTP Status Code لأن رقم الموبايل مش موجود في الداتابيز
    protected $code = 404;

    public function __construct()
    {
        // مفتاح الترجمة اللي هنحطه في ملف الـ messages
        parent::__construct('errors.mobile_not_found');
    }
}
