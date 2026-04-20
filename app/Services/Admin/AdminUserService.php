<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Exceptions\Users\UserNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserService
{
    /**
     * جلب كل المستخدمين مع الفلترة والبحث
     */
    public function getAllUsers(array $filters, int $perPage = 10): LengthAwarePaginator
    {
       return User::withTrashed()->with([ 'user_mobiles'])

          ->when(filled($filters['role'] ?? null), function ($query) use ($filters)){
            $query->where('role',$filters['role']);
          }
           ->when(filled($filters['email_verified'] ?? null), function ($query) use ($filters)){
            $query->where('email_verified',$filters['email_verified']);
          }
           ->when(filled($filters['deleted_at'] ?? null), function ($query) use ($filters)){
            $query->where('deleted_at',$filters['deleted_at']);
          }
            ->when(filled($filters['search'] ?? null), function ($query) use ($filters) {
                $search = $filters['search']
                   $query->where(function ($q) use ($search) 
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    // البحث في جدول الموبايلات (العلاقة)
                    ->orWhereHas('user_mobiles', function ($mobileQuery) use ($search) {
                        $mobileQuery->where('number', 'like', "%$search%");
                        // تأكد من اسم العمود في جدول الموبايلات (غالباً هو number أو phone)
                    });
                });
                        ->latest()
        ->paginate($perPage);

    }

    /**
     * جلب مستخدم واحد بالتفاصيل
     */
    public function getUserById(int $id): User
    {
        // هنا ابحث عن المستخدم وارمي UserNotFoundException لو مش موجود
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function updateUser(int $id, array $data): User
    {
        // هنا حدث البيانات واهتم بموضوع الـ email_verified_at
    }

    /**
     * حذف مستخدم (Soft Delete)
     */
    public function deleteUser(int $id): bool
    {
        // امسح اليوزر هنا
    }

    /**
     * استعادة مستخدم محذوف
     */
    public function restoreUser(int $id): bool
    {
        // رجع اليوزر المحذوف (استخدم onlyTrashed)
    }

    /**
     * تبديل حالة الحظر (Block/Unblock)
     */
    public function toggleUserBlock(int $id): string
    {
        // اعكس حالة الحظر ورجع رسالة مناسبة
    }
}
