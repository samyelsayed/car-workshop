<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\loginRequest;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{use ApiTrait;
    public function login(loginRequest $request){
        $user =User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password ,$user->password )){
            return $this->ErrorMessage([''],'Invalid Credentials',401);
        }elseif(is_null($user->email_verified_at)){
            return $this->ErrorMessage([''],'Your email is not verified. Please verify your email first',401);
        }
         $user->token = 'Bearer ' . $user->createToken($request->device_name)->plainTextToken;
         return $this->Data(compact('user'));

    }

    public function logoutAllDevices(Request $request){

    $authenticatedUser = Auth::guard('sanctum')->user();
    $authenticatedUser->tokens()->delete();
    return $this->SuccessMessage('logged out of **all** devices');

    }


    public function logout(Request $request){
        $authenticatedUser = Auth::guard('sanctum')->user();
        // $token = $request->header('Authorization');
        // $BeararTokenId = explode('|',$token)[0];
        // $tokenId =  explode('| ',$BeararTokenId)[1];
        // $authenticatedUser->tokens()->where('id',$tokenId)->delete();

        $authenticatedUser->currentAccessToken()->delete();
        return $this->SuccessMessage('You have successfully logged out of this device.');
    }
}

