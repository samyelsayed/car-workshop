<?php

namespace App\Exceptions\Orders;

use Exception;

use App\Exceptions\BaseException;

class OrderNotFoundException extends BaseException
{
    // بنحدد الرسالة الافتراضية وكود الخطأ
    protected $message = 'Sorry, this request is not in our archive.';
    protected $code = 404;
}