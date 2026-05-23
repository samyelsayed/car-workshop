<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\User\UserBlockedException;
use App\Exceptions\User\UserNotVerifiedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function login(array $data): User
    {
        // 1. البحث عن المستخدم
        $user = $this->findUserByEmail($data['email']);

        // 2. التحقق من البيانات (بترمي Exception لو فيه غلط)
        $this->verifyCredentials($user, $data['password']);
        $this->checkEmailVerification($user);
        if ($user->blocked_at) {
            throw new UserBlockedException();
        }

        // 3. توليد التوكن (استخدمنا ميثود خاصة عشان لو حبيت تغير شكل التوكن مستقبلاً)
        $user->token = $this->createDeviceToken($user, $data['device_name']);

        return $user;
    }

    public function adminLogin(array $data): User
    {
        // بدل ما نكرر الكود.. بننادي الـ login العادية
        $user = $this->login($data);


        // وبنزود شرط الأدمن بس
        $this->checkAdminRole($user);

        return $user;
    }

    protected function findUserByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new InvalidCredentialsException();
        }
        return $user;
    }

    protected function verifyCredentials(User $user, string $password): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }
    }

    protected function checkEmailVerification(User $user): void
    {
        if (is_null($user->email_verified_at)) {
             throw new UserNotVerifiedException();
        }
    }

    protected function checkAdminRole(User $user): void
    {
        if ($user->role !== 'admin') {
            throw new \Exception('Unauthorized Access', 403);
        }
    }

    protected function createDeviceToken(User $user, string $deviceName): string
    {
        return 'Bearer ' . $user->createToken($deviceName)->plainTextToken;
    }



        public function logout(User $user): void
        {
            // حذف التوكن الحالي فقط
            $user->currentAccessToken()->delete();
        }

        public function logoutAllDevices(User $user): void
        {
            // حذف كل التوكنز المسجلة لهذا المستخدم
            $user->tokens()->delete();
        }



}
