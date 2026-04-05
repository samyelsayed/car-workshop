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

            return $user;
        });
    }

    protected function createUser(array $data): User
    {
        return User::create([
            'first_name' => $data['firstName'],
            'last_name'  => $data['lastName'],
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

        $user->forceFill([
            'code'            => $code,
            'code_expires_at' => now()->addMinutes(5),
            'code_purpose'    => 'email_verification',
        ])->save();

        $user->notify(new SendOtpNotification($code));
    }
}
