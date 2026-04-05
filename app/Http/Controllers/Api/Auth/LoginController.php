<?php

// namespace App\Http\Controllers\Api\Auth;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Api\Auth\loginRequest;
// use App\Http\Resources\UserResource;
// use App\Http\Traits\ApiTrait;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;






// class LoginController extends Controller
// {use ApiTrait;


//     public function login(loginRequest $request){
//         $user =User::where('email',$request->email)->first();
//         if(!$user || !Hash::check($request->password ,$user->password )){
//             return $this->ErrorMessage([''],'Invalid Credentials',401);
//         }elseif(is_null($user->email_verified_at)){
//             return $this->ErrorMessage([''],'Your email is not verified. Please verify your email first',401);
//         }
//          $user->token = 'Bearer ' . $user->createToken($request->device_name)->plainTextToken;
//          return $this->Data(compact('user'));

//     }





//         public function adminLogin(loginRequest $request){
//             $user =User::where('email',$request->email)->first();
//             if(!$user || !Hash::check($request->password ,$user->password )){
//                 return $this->ErrorMessage([],'Invalid Credentials',401);
//             }

//             if(is_null($user->email_verified_at)){
//                 return $this->ErrorMessage([],'Your email is not verified. Please verify your email first',401);
//             }
//             if($user->role != 'admin'){
//                 return $this->ErrorMessage([],'Unauthorized Access',403);
//             }
//         $deviceName = $request->device_name ?? 'web_admin_panel';
//         $user->token = 'Bearer ' . $user->createToken($deviceName)->plainTextToken;
//          return $this->Data(new UserResource($user), 'Admin logged in successfully');


//         }

//     public function logoutAllDevices(Request $request){

//     $authenticatedUser = Auth::guard('sanctum')->user();
//     $authenticatedUser->tokens()->delete();
//     return $this->SuccessMessage('logged out of all devices');

//     }


//     public function logout(Request $request){
//         $authenticatedUser = Auth::guard('sanctum')->user();
//         // $token = $request->header('Authorization');
//         // $BeararTokenId = explode('|',$token)[0];
//         // $tokenId =  explode('| ',$BeararTokenId)[1];
//         // $authenticatedUser->tokens()->where('id',$tokenId)->delete();

//         $authenticatedUser->currentAccessToken()->delete();
//         return $this->SuccessMessage('You have successfully logged out of this device.');
//     }
// }




namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\loginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiTrait;

    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(loginRequest $request)
    {
        // سطر واحد بيخلص الليلة
        $user = $this->loginService->login($request->validated());

        return $this->Data(compact('user'), 'Logged in successfully');
    }

    public function adminLogin(loginRequest $request)
    {
        $user = $this->loginService->adminLogin($request->validated());

        return $this->Data(new UserResource($user), 'Admin logged in successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('sanctum')->user()->currentAccessToken()->delete();
        return $this->SuccessMessage('Logged out successfully');
    }

    public function logoutAllDevices(Request $request)
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        return $this->SuccessMessage('Logged out of all devices');
    }
}
