<?php

// namespace App\Exceptions;

// use Exception;
// use Illuminate\Http\JsonResponse;

// class BaseException extends Exception
// {
//     // ميثود الرندر هنا اتكتبت مرة واحدة بس للعيلة كلها
//     public function render($request): JsonResponse
//     {
//         return response()->json([
//             'status'  => $this->getCode() ?: 400, // بياخد الكود من الكلاس الابن
//             'message' => $this->getMessage(),      // بياخد الرسالة من الكلاس الابن
//             'data'    => null,
//             'errors'  => []
//         ], $this->getCode() ?: 400);
//     }
// }


namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Base Exception class for all custom exceptions
 *
 * Automatically translates error messages from lang files
 * and provides unified JSON response structure
 */
abstract class BaseException extends Exception
{
    /**
     * Default error code
     */
    protected $code = 400;

    /**
     * Constructor
     *
     * @param string|null $customMessage Custom message key or null to use default
     */
    public function __construct(?string $customMessage = null)
    {
        // 1. Determine message key (custom or default from child class)
        $messageKey = $customMessage ?: $this->message;

        // 2. Auto translate from lang/../errors.php
        $translatedMessage = __($messageKey);

        // 3. Pass translated message and code to parent
        parent::__construct($translatedMessage, $this->code);
    }

    /**
     * Render exception as JSON response
     */
    public function render($request): JsonResponse
    {
        $statusCode = $this->getCode() ?: 400;

        return response()->json([
            'message' => $this->getMessage(),
            'errors' => (object)[],
            'data' => (object)[]
        ], $statusCode);
    }
}



