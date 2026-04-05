<?php

// namespace App\Http\Controllers\Api\Auth;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Api\Auth\RegisterRequest;
// use App\Http\traits\ApiTrait;
// use App\Models\User;
// use App\Models\UserMobile;
// use App\Notifications\SendOtpNotification;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;


// class RegisterController extends Controller
// { use ApiTrait;
//     /**
//      * Handle the incoming request.
//      */
//     public function __invoke(RegisterRequest $request)
//     {
//         $data = $request->except('password','password_confirmation','phone');
//         $data['password']= Hash::make($request->password);
//         $user= User::create($data);

//         $user_phone= UserMobile::create(['user_id'=>$user->id,'mobile_number'=>$request->phone]);

//         //send code to email
//         $code = rand(1000, 9999);
//         $user->code = $code;
//         $user->code_expires_at = now()->addMinutes(5);
//         $user->save();
//         $user->notify(new SendOtpNotification($code));

//          return $this->Data(['user_id' => $user->id],'User registered successfully');
//     }
// }






namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use ApiTrait;

    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * Register a new user
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerService->register($request->validated());

        return $this->Data(
            new UserResource($user),
            'User registered successfully'
        );
    }
}
