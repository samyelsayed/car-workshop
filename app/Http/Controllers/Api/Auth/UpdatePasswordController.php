<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\updatePassword;
use App\Http\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{ use ApiTrait;
    public function updatePassword(updatePassword $request){
        $user = $request->user();
        if(!Hash::check($request->old_password,$user->password)){
        return $this->ErrorMessage(['old_password' => ['Incorrect password']],'the old password is error try again',400);
        }
        else{
            $user->password= Hash::make($request->new_password);
            $user->save();
            $user->tokens()->delete();
            $token = $user->createToken($request->device_name)->plainTextToken;
            $user->token = $token;
            return $this->Data(['user'=>$user],'password updated successfully');
        }

    }
  


}
