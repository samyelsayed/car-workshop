<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SendOtpRequest;
use App\Http\Requests\Api\Auth\VerifyCodeRequest;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    use ApiTrait;

    private function generateCode($user)
    {
        $code = rand(1000, 9999);
        $user->code = $code;
        $user->code_expires_at = now()->addMinutes(5);
        $user->save();

        $user->notify(new SendOtpNotification($code));
    }

    public function sendCode(SendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();
       if (!$user) {
     return $this->ErrorMessage(['email' => ['User not found']],'User does not exist',404 );

        }elseif($user->email_verified_at) {
        return $this->errorMessage(
            ['email' => ['Email already verified']],
            'Already verified',
            400
        );
    }
        $this->generateCode($user);
        return $this->Data(['email' => $user->email], 'Verification code sent successfully', 200);
    }




    public function checkCode(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
     return $this->ErrorMessage(['email' => ['User not found']],'User does not exist',404 );
        }
        if ($request->code != $user->code || $user->code_expires_at < now()) {
            return $this->ErrorMessage(['code' => ['Invalid or expired verification code']],'Verification failed',400);
        } elseif($request->code == $user->code && $user->code_expires_at >= now()) {
            $user->email_verified_at = now();
            $user->code = null;
            $user->code_expires_at = null;
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;


            return $this->Data(['user' => $user,'token' => $token], 'Email verified successfully');
        }
    }






    public function reSendCode(SendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
          return $this->ErrorMessage(['email' => ['User not found']],'User does not exist',404 );
        }elseif($user->email_verified_at) {
           return $this->errorMessage(['email' => ['Email already verified']],'Already verified',400);
    }
        $this->generateCode($user);
        return $this->Data(['email' => $user->email], 'Verification code re-sent successfully', 200);
    }
}
