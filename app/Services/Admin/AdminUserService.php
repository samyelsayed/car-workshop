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
        // اكتب هنا لوجيك الفلترة والبحث واستخدم withTrashed
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