<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class EmailAlreadyVerifiedException extends BaseException
{
    // الـ HTTP Status Code لحالة طلب غير منطقي لأن الحساب متفعل
    protected $code = 400;

    public function __construct()
    {
        // ممرر مفتاح الترجمة اللي هيكون جوه ملف الـ messages
        parent::__construct('errors.email_already_verified');
    }
}
