<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ForgotPasswordService
{

//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورهاتعمل سيند كود
 public function sendOtpFlow(array $data): User
    {
        $user= $this->findUserByEmail($data['email']);
        $code =$this->generateRandomCode($user);
        $this->sendOtp($user,$code);
        return $user;

    }

//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورها تعمل تشيك كود
 public function verifyOtpFlow(array $data , int $code): array
    {
        $user= $this->findUserByEmail($data['email']);
        $this->checkCode($user,$code);
        $this->clearOtp($user);
        $token = $this->generateToken($user);
        return [
        'user'  => $user,
        'token' => $token
    ];
    }


//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورها تعمل ريسيت باسورد
public function resetPasswordFlow(array $data ): User
    {
        $user= $this->findUserByEmail($data['email']);
        $this->checkToken($user,$data['token']);
        $this->hashNewPassword($user,$data['password']);
        $this->createDeviceToken($user,'Reset Password Token');
        return $user;
    }




    protected function findUserByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('Invalid Credentials', 401);
        }
        return $user;
    }



    //انشاء الكود العشوائي
       protected function generateRandomCode(User $user) :int
       {
            $code =random_int(1000,9999);
                $user->code = $code;
                $user->code_expires_at= now()->addMinutes(5);
                $user->save();
                return $code;
       }


        protected function sendOtp(User $user, int $code): void
    {
        Mail::to($user->email)->send(new ResetPasswordMail($user->first_name, $code));
    }

        protected function checkCode(User $user, int $code): void
    {
        if($user->code != $code || now()->greaterThan($user->code_expires_at) ){
        throw new \Exception('Invalid or expired verification code', 400);  }
    }



    protected function verifyEmail(User $user): void
    {
        $user->update([
            'email_verified_at' => now()
        ]);
    }



     protected function clearOtp(User $user): void
    {
        $user->update([
            'code' => null,
            'code_expires_at' => null
        ]);
    }

    protected function generateToken(User $user): string
    {
                $token = Str::random(60);
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]);

                $user->save();
                return $token;
    }


     protected function checkToken(User $user , $token): void{

       $resetData =   DB::table('password_reset_tokens')->where('email', $user->email)->first();

            if(!$resetData ||!Hash::check($token, $resetData->token)){
                 throw new \Exception('Error. Please try again at another time.', 400);  }
    }


    protected function hashNewPassword(User $user, string $password): void{

            $user->password = Hash::make($password);
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            $user->save();

  }

    protected function createDeviceToken(User $user, string $deviceName): string
    {
        $token=  'Bearer ' . $user->createToken($deviceName)->plainTextToken;
$user->token = $token;
$user->save();
        return $token;

    }


}
