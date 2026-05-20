<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserMobile;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    public function register(array $data): User
    {
       // Wrap in transaction for data integrity
        return DB::transaction(function () use ($data) {

            $user = $this->createUser($data);

            $this->createUserMobile($user, $data['phone']);

            $this->sendVerificationNotification($user);

            // 🔥 التعديل السحري هنا قبل ما الـ transaction تقفل وترجع اليوزر:
        // ==========================================
        $user->refresh();                                          // سحب الـ default role من الداتابيز

        return $user;
        });
    }

protected function createUser(array $data): User
{
    return User::create([
        'first_name' => $data['first_name'], 
        'last_name'  => $data['last_name'],  
        'email'      => $data['email'],
        'password'   => $data['password'], 
    ]);
}

    protected function createUserMobile(User $user, string $phone): void
    {
        UserMobile::create([
            'user_id'       => $user->id,
            'mobile_number' => $phone
        ]);
    }




    protected function sendVerificationNotification(User $user): void
    {
        $code = random_int(1000, 9999);

        // تعديل الحقول هنا عشان تطابق أسماء أعمدة جدول الـ users الجديد بالملي 👇
        $user->forceFill([
            'code'            => $code,
            'code_expires_at' => now()->addMinutes(5),
        ])->save();

        $user->notify(new SendOtpNotification($code));
        
    }
}
