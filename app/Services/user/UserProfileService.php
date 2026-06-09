<?php

namespace App\Services\User;


use App\Exceptions\User\Email\UserBlockedException;
use App\Exceptions\User\Email\UserNotVerifiedException;
use App\Http\Traits\HandlesImageUpload;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserProfileService
{
    use HandlesImageUpload;

    // مسار مجلد صور المستخدمين ثابت ومحدد
    protected string $folder = 'images/users';

    /**
     * فحص حالة المستخدم وصحته الحسابية
     */
    public function checkUserHealth(User $user): void
    {
        if ($user->status === 'blocked') {
            throw new UserBlockedException();
        }

        if (!$user->hasVerifiedEmail()) {
            throw new UserNotVerifiedException();
        }
    }

    /**
     * تحديث بيانات المستخدم والصورة الشخصية
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            
            // لو الـ Request فيه صورة جديدة
            if (isset($data['image'])) {
                // التريت هيمسح القديمة (لو مش ديفولت) ويرفع الجديدة ويرجع "المسار النصي" فقط
                $data['image'] = $this->uploadImage( $data['image'],$this->folder, $user->image );// الصورة القديمة للـ Smart Delete
            }

            // تحديث البيانات كلها في خطوة واحدة خطافية
            $user->update($data);

            return $user->fresh();
        });
    }

    /**
     * دالة منفصلة فقط لو اليوزر حب يضغط على زرار "حذف الصورة الشخصية" 
     */
    public function removeProfileImage(User $user): User
    {
        if ($user->image) {
            $this->deleteImage($user->image);
            $user->update(['image' => null]);
        }

        return $user->fresh();
    }
}