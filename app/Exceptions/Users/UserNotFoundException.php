<?php
namespace App\Exceptions\Users;

use App\Exceptions\BaseException;

class UserNotFoundException extends BaseException
{
    // هنا بنحدد الرسالة الافتراضية وكود الخطأ
    protected $message = 'The user does not exist or has been permanently deleted.';
    protected $code = 404;

}