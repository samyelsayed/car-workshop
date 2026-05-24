<?php
namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderAlreadyCancelledException extends BaseException
{
    // الـ Status Code المناسب هنا هو 400 (Bad Request) لأن الطلب غير منطقي في حالته الحالية
    protected $code = 400; 

    public function __construct()
    {
        parent::__construct('errors.order_already_cancelled');
    }
}