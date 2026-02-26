<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\traits\ApiTrait;
use App\Models\User;
use App\Models\UserMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{ use ApiTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $data = $request->except('password','confirm_password','phone');
        $data['password']= Hash::make($request->password);
        $user= User::create($data);

        $user_phone= UserMobile::create(['user_id'=>$user->id,'mobile_number'=>$request->phone]);
         return $this->SuccessMessage('User registered successfully');
    }
}
