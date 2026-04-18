<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BaseException extends Exception
{
    // ميثود الرندر هنا اتكتبت مرة واحدة بس للعيلة كلها
    public function render($request): JsonResponse
    {
        return response()->json([
            'status'  => $this->getCode() ?: 400, // بياخد الكود من الكلاس الابن
            'message' => $this->getMessage(),      // بياخد الرسالة من الكلاس الابن
            'data'    => null,
            'errors'  => []
        ], $this->getCode() ?: 400);
    }
}