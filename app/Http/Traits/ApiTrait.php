<?php

namespace App\Http\Traits;

trait ApiTrait {
//     public function SuccessMessage(string $message = "",int $code = 200)
//     {
//         return response()->json(
//             [
//                 'message'=>$message,
//                 'errors'=>(object)[],
//                 'data'=>(object)[],
//             ],
//             $code
//         );
//     }

//     public function ErrorMessage(Array $errors , string $message = "",int $code = 422)
//     {
//         return response()->json(
//             [
//                 'message'=>$message,
//                 'errors'=> $errors,
//                 'data'=>(object)[],
//             ],
//             $code
//         );
//     }

//     public function Data( $data,string $message = "",int $code = 200)
//     {
//         return response()->json(
//             [
//                 'message'=>$message,
//                 'errors'=>(object)[],
//                 'data'=>$data,
//             ],
//             $code
//         );
//     }
// }
public function SuccessMessage(string $message = "", int $code = 200)
    {
        return response()->json(
            [
                // دالة __() هتدور على المفتاح في ملفات اللغة، لو ملقيتوش هترجع النص زي ما هو
                'message' => __($message),
                'errors' => (object)[],
                'data' => (object)[],
            ],
            $code
        );
    }

    public function ErrorMessage(array $errors, string $message = "", int $code = 422)
    {
        return response()->json(
            [
                'message' => __($message),
                'errors' => $errors,
                'data' => (object)[],
            ],
            $code
        );
    }

    public function Data($data, string $message = "", int $code = 200)
    {
        return response()->json(
            [
                'message' => __($message),
                'errors' => (object)[],
                'data' => $data,
            ],
            $code
        );
    }
}
