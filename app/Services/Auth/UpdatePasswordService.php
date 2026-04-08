<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UpdatePasswordService
{

//الميثود المجمعه الي هتعمل ابديت للباسورد
public function updatePasswordFlow(User $user, array $data): array
    {
        $this->verifyCurrentPassword($user,$data['currentPassword']);
        $this->updatePassword($user,$data['newPassword']);
        $this->deleteTokens($user);
        return $this->generateNewToken($user, $data['deviceName']);
    }



// -ميثود تتشيك ع الباسورد تقرانه ب الي ف الداتا بيز
protected function verifyCurrentPassword($user, $currentPassword): void
    {
       if(!Hash::check($data['currentPassword'],$user->password)){
        throw new \Exception('Incorrect password', 400);
        }

    }

protected function updatePassword(User $user, string $newPassword): void
    {
        $user->password= Hash::make($newPassword);
        $user->save();

    }

    protected function deleteTokens(User $user): void{
        $user->tokens()->delete();
    }

    protected function generateNewToken(User $user,string $deviceName): array{
        $token = $user->createToken($deviceName)->plainTextToken;
        $user->token = $token;
        return [
            'user' => $user,
            'token' => $token
        ];
    }


// -ميثود تمسح القديم وتحفظ الجديد في الداتا بيز

}
