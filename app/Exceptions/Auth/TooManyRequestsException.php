<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class TooManyRequestsException extends BaseException
{
    // الرسالة المترجمة أو النصية بتاعتك
    protected $message = 'errors.too_many_attempts'; 
    protected $code = 429; // كود الحالة الصح للـ Rate Limit
}