<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\SendOtpRequest;
use App\Http\Requests\Api\Auth\VerifyCodeRequest;
use App\Http\Traits\ApiTrait;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{use ApiTrait;
       public function sendCode(SendOtpRequest $request){
            $user = User::where('email',$request->email)->first();
            if(! $user){
            return $this->ErrorMessage(['email' => ['User not found']],'User does not exist',404);
            }else{
                $code =random_int(1000,9999);
                $user->code = $code;
                $user->code_expires_at= now()->addMinutes(5);
                $user->save();

                // $token = Str::random(60);
                // DB::table('password_reset_tokens')->updateOrinsert(
                // ['email'=>$request->email],
                // ['token'=>Hash::make($token)],
                // ['created_at'=>now()]
                // );
                Mail::to($user->email)->send(new ResetPasswordMail($user->first_name, $code));
                return $this->Data(['email'=>$request->email],'OTP sent successfully',200);
            }
        }

        public function checkCode(VerifyCodeRequest $request){
        $user = User::where('email',$request->email)->first();
        if(! $user){
            return $this->ErrorMessage(['email' => ['User not found']],'User does not exist',404);
        }elseif($user->code != $request->code || now()->greaterThan($user->code_expires_at) ){
        return $this->ErrorMessage(['code' => ['Invalid or expired verification code']],'Verification failed',400);
            }else{
                $token = Str::random(60);
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                DB::table('password_reset_tokens')->insert(
                ['email'=>$request->email],
                ['token'=>Hash::make($token)],
                ['created_at'=>now()]
                );
                $user->code = null;
                $user->code_expires_at = null;
                $user->save();
                return $this->Data(['token'=>$token,'email'=>$request->email],'Verification successful',200);

            }
        }


        public function resetPassword(ResetPasswordRequest $request){
            $user = User::where('email',$request->email)->first();
            $resetData =   DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if(!$resetData ||!Hash::check($request->token, $resetData->token)){
            return $this->ErrorMessage(['email'=>$request->email],'Error. Please try again at another time.',400);
            }else{
            $user->password = Hash::make($request->password);
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            $user->save();
            $user->token = 'Bearer ' . $user->createToken($request->device_name)->plainTextToken;
            return $this->Data(compact('user'),'Password reset successfully',200);

            }
        }

}
