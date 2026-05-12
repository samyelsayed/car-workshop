<?php
namespace App\Services\Admin;

use App\Exceptions\Order\OrderAlreadyCompletedException;
use App\Exceptions\Order\OrderCannotBeModifiedException;
use App\Exceptions\Order\OrderNotFoundException;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminOrderService
{
    /**
     * جلب كل الطلبات مع الفلترة والبحث
     */
public function getAllOrders(array $filters, int $perPage = 10): LengthAwarePaginator
{
    return Order::with(['user', 'car'])
        // فلتر الحالة - باستخدام filled للتعامل الذكي مع البيانات
        ->when(filled($filters['status'] ?? null), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        })

        // فلتر المستخدم
        ->when(filled($filters['user_id'] ?? null), function ($query) use ($filters) {
            $query->where('user_id', $filters['user_id']);
        })

        // فلتر التاريخ من
        ->when(filled($filters['from_date'] ?? null), function ($query) use ($filters) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        })

        // فلتر التاريخ إلى
        ->when(filled($filters['to_date'] ?? null), function ($query) use ($filters) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        })

        // البحث الشامل - الـ filled هنا بتحميك لو اليوزر بعت مسافات في خانة البحث
        ->when(filled($filters['search'] ?? null), function ($query) use ($filters) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%$search%")
                            ->orWhere('last_name', 'like', "%$search%");
                    })
                    ->orWhereHas('car', function ($q3) use ($search) {
                        $q3->where('plate_number', 'like', "%$search%");
                    });
            });
        })
        ->latest()
        ->paginate($perPage);
}




    /**
     * جلب تفاصيل طلب واحد بكل علاقاته
     */
    public function getOrderDetails(int $id): Order
    {
         $order = Order::with(['user', 'car', 'inspections', 'workProgress', 'orderItems.service'])->find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }



    /**
     * تحديث حالة الطلب (مثلاً: pending -> in_progress)
     */
    public function updateOrderStatus(int $id, string $status): Order
    {
         $order =$this->getOpenOrder($id);
        $order->status = $status;
        $order->save();

        return $order;
        // التحقق من أن الطلب ليس completed أو cancelled
    }

    /**
     * إلغاء الطلب
     */
    public function cancelOrder(int $id): Order
    {
          $order =$this->getOpenOrder($id);
          $order->status = 'cancelled';
          $order->save();

          return $order;
         }
/**
     * ميثود مساعدة مطورة للتأكد من حالة الطلب وصلاحية التعديل
     */
    private function getOpenOrder(int $id): Order
    {
        $order = Order::find($id);

        // 1. التأكد من الوجود أولاً
        if (!$order) {
            throw new OrderNotFoundException();
        }

        // 2. إذا كان الطلب مكتمل (حالة نجاح نهائية)
        if ($order->status === 'completed') {
            throw new OrderAlreadyCompletedException();
        }

        // 3. إذا كان الطلب ملغي (حالة فشل نهائية تمنع التعديل)
        if ($order->status === 'cancelled') {
            throw new OrderCannotBeModifiedException();
        }

        return $order;
    }
}

