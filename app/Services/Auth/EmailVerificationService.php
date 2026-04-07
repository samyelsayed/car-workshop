<?php
namespace App\Services\Auth;

use App\Notifications\SendOtpNotification;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class EmailVerificationService
{


//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورها تعمل الكود وتحفظة  و تبعته ع الاميل وتتشيك هل الاميل متفعل اصلا ولا لا
public function sendOtpFlow(array $data): User {
    $user = $this->findUserByEmail($data['email']);
    $this->isEmailVerified($user);
    $code = $this->generateRandomCode($user);
    $this->sendCode($user ,$code );
    return $user;
}




//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورها تعمل تتشيك ع الكود ولو تمام تخلي الاميل مفعل

public function verifyOtpFlow(array $data, int $code): array {
$user = $this->findUserByEmail($data['email']);
$this->checkCode($user ,$code);
$this->verifyEmail($user);
$token = $this->generateToken($user);
return [
    'user' => $user,
    'token' => $token
];
}
//القانشكن الكبيرة  الي هجمع فيها الميثود الي دورها تعمل  اعادة ارسال الكود بتشوف الاميل مفعل ولا لا ولو لا تعيد ارسال الكود

public function resendOtpFlow(array $data): User 
    {
        return $this->sendOtpFlow($data);
    }










//ميثود تجيب الوزر من الاميل ووتشك هل موجود ولا لا
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
//ميثود تتشيك هل الاميل متفعل ولا  لا
    protected function isEmailVerified(User $user) :void
    {
        if($user->email_verified_at){
            throw new \Exception('Email already verified', 400);
        }
    }
//ميثود تبعت الكود ع الاميل
    protected function sendCode(User $user ,int $code ) :void
    { 
        $user->notify(new SendOtpNotification($code));
    }
//ميثود تشيك الكود
    protected function checkCode(User $user , $code) :void
    { 
        if($code != $user->code || $user->code_expires_at < now()) {
            throw new \Exception('Invalid or expired code', 400);
        }
    }


//ميثود تمسح الكود من الداتا بيز وتخلي الاميل مفعل
   protected function verifyEmail(User $user ) :void
    { 
      
            $user->email_verified_at = now();
            $user->code = null;
            $user->code_expires_at = null;
            $user->save();
    

    }



    //ميثود تولد توكن للمستخدم بعد ما يتفعل
    protected function generateToken(User $user): string{
        $token = $user->createToken('auth_token')->plainTextToken;
        return $token;

    }

}
