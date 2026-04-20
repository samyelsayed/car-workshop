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
    return User::withTrashed()->with(['user_mobiles'])
        ->when(filled($filters['trashed_status'] ?? null), function ($query) use ($filters) {
        if ($filters['trashed_status'] === 'only') {
            // يجلب المحذوفين فقط
            $query->onlyTrashed();
        } elseif ($filters['trashed_status'] === 'without') {
            // يجلب غير المحذوفين فقط (يلغي تأثير withTrashed)
            $query->withoutTrashed();
        }
        // لو مبعتش حاجة أو بعت قيمة تانية، هيفضل شغال بالـ withTrashed اللي فوق ويجيب الكل
        })
        // فلتر الرتبة
        ->when(filled($filters['role'] ?? null), function ($query) use ($filters) {
            $query->where('role', $filters['role']);
        })
        // فلتر المفعلين (اللي إيميلهم مش null)
        ->when(filled($filters['email_verified_at'] ?? null), function ($query) {
            $query->whereNotNull('email_verified_at');
        })
        // البحث الشامل
        ->when(filled($filters['search'] ?? null), function ($query) use ($filters) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('user_mobiles', function ($mobileQuery) use ($search) {
                      $mobileQuery->where('number', 'like', "%$search%");
                  });
            });
        })
        ->latest()
        ->paginate($perPage);
}


    /**
     * جلب مستخدم واحد بالتفاصيل
     */
    public function getUserById(int $id): User
    {
        $user =User::withTrashed()->with(['user_mobiles', 'addresses', 'cars', 'orders'])->find($id);
        if(! $user){
          throw new UserNotFoundException();
            }
        return $user;

    }

    /**
     * تحديث بيانات مستخدم
     */
    public function updateUser(int $id, array $data): User
    {
     $user = $this->getUserById($id);

        // إذا تم تغيير الإيميل، نجعل الحالة غير مفعلة
        if (isset($data['email']) && $user->email !== $data['email']) {
            $user->email_verified_at = null;
        }

        $user->update($data);
        return $user;
    }

    /**
     * حذف مستخدم (Soft Delete)
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        return $user->delete();
    }

    /**
     * استعادة مستخدم محذوف
     */
    public function restoreUser(int $id): bool
    {
        $user = User::onlyTrashed()->find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user->restore();
    }

    /**
     * تبديل حالة الحظر (Block/Unblock)
     */
    public function toggleUserBlock(int $id): User
    {
        $user = $this->getUserById($id);
        
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return $user->fresh();
    }
}
