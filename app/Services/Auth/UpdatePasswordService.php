<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordService
{
    /**
     * Change user password
     */
    public function changePassword(User $user, array $data): string
    {
        return DB::transaction(function () use ($user, $data) {
            $this->verifyCurrentPassword($user, $data['current_password']);
            $this->updatePassword($user, $data['new_password']);
            $this->revokeAllTokens($user);

            return $this->generateNewToken($user, $data['device_name']);
        });
    }

    /**
     * Verify current password
     */
    protected function verifyCurrentPassword(User $user, string $currentPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Current password is incorrect', 400);
        }
    }

    /**
     * Update user password
     */
    protected function updatePassword(User $user, string $newPassword): void
    {
        $user->password = $newPassword;  // Model cast will hash it
        $user->save();
    }

    /**
     * Revoke all user tokens
     */
    protected function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Generate new access token
     */
    protected function generateNewToken(User $user, string $deviceName): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}
