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
        $user->email_verification_code = $code;
        $user->email_verification_expires_at = now()->addMinutes(5);
        $user->save();

        $user->notify(new SendOtpNotification($code));
    }

    public function sendCode(SendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $this->generateCode($user);
        return $this->Data(['email' => $user->email], 'Verification code sent successfully', 200);
    }

    public function checkCode(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if ($request->code != $user->email_verification_code || $user->email_verification_expires_at < now()) {
            return $this->Data(compact('user'), 'Code is invalid or expired', 422);
        } else {
            $user->email_verified_at = now();
            $user->email_verification_code = null;
            $user->email_verification_expires_at = null;
            $user->save();
            return $this->Data(compact('user'), 'Email verified successfully', 200);
        }
    }

    public function reSendCode(SendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $this->generateCode($user);
        return $this->Data(['email' => $user->email], 'Verification code re-sent successfully', 200);
    }
}
