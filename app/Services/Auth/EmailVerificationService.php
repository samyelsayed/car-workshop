<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\EmailAlreadyVerifiedException;
use App\Exceptions\Auth\InvalidOrExpiredCodeException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailVerificationService
{
    // الفانكشن الكبيرة: تجمع الميثودز اللي دورها تعمل الكود وتحفظه وتبعته ع الإيميل وتتشيك هل الإيميل متفعل أصلاً ولا لأ
    public function sendOtpFlow(array $data): User {
        $user = $this->findUserByEmail($data['email']);
        $this->isEmailVerified($user);
        $code = $this->generateRandomCode($user);
        $this->sendCode($user, $code);
        return $user;
    }
public function verifyOtpFlow(array $data, int $code): array {
    $user = $this->findUserByEmail($data['email']);

    // 1️⃣ اسأل الأول: هل هو متفعل؟ لو متفعل هيضرب إيرور "Email already verified" ويوقف هنا
    $this->isEmailVerified($user);

    // 2️⃣ لو مش متفعل؟ يكمل عادي ويدخل يتشيك على صحة الكود
    $this->checkCode($user, $code);

    $this->verifyEmail($user);
    $token = $this->generateToken($user);
    return [
        'user'  => $user,
        'token' => $token
    ];
}


    // الفانكشن الكبيرة: تجمع الميثودز اللي دورها تعمل إعادة إرسال الكود بتشوف الإيميل مفعل ولا لأ ولو لأ تعيد إرسال الكود
    public function resendOtpFlow(array $data): User {
        $user = $this->findUserByEmail($data['email']);
        $this->isEmailVerified($user);
        $code = $this->generateRandomCode($user);
        $this->sendCode($user, $code);
        return $user;
    }

    // ميثود تجيب اليوزر من الإيميل وتتشيك هل موجود ولا لأ
    protected function findUserByEmail(string $email): User {
        $user = User::where('email', $email)->first();
        if (!$user) {
           throw new UserNotFoundException();
        }
        return $user;
    }

    // إنشاء الكود العشوائي وحفظه (تم تعديل أسماء الأعمدة للأسماء الصحيحة بالملي ✅)
    protected function generateRandomCode(User $user): int {
        $code = random_int(1000, 9999);
        $user->code = $code; // ✅ تعديل
        $user->code_expires_at = now()->addMinutes(5); // ✅ تعديل
        $user->save();
        return $code;
    }

    // ميثود تتشيك هل الإيميل متفعل ولا لا
    protected function isEmailVerified(User $user): void {
        if ($user->email_verified_at) {
           throw new EmailAlreadyVerifiedException();
        }
    }

    // ميثود تبعت الكود ع الإيميل
    protected function sendCode(User $user, int $code): void {
        $user->notify(new SendOtpNotification($code));
    }

    // ميثود تشيك الكود (تم تعديل أسماء الأعمدة للأسماء الصحيحة بالملي ✅)
    protected function checkCode(User $user, $code): void {
        if ($code != $user->code || $user->code_expires_at < now()) { // ✅ تعديل
            throw new InvalidOrExpiredCodeException();
        }
    }

    // ميثود تمسح الكود من الداتا بيز وتخلي الإيميل مفعل (تم تعديل أسماء الأعمدة للأسماء الصحيحة بالملي ✅)
    protected function verifyEmail(User $user): void {
        $user->email_verified_at = now();
        $user->code = null; // ✅ تعديل
        $user->code_expires_at = null; // ✅ تعديل
        $user->save();
    }

    // ميثود تولد توكن للمستخدم بعد ما يتفعل
    protected function generateToken(User $user): string {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
