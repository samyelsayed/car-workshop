<?php
namespace App\Services\Admin;

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
        //فلتر الحالة
        ->when(isset($filters['status']),function ($query) use ($filters){
            $query->where('status',$filters['status']);
        })
        //فلتر المستخدم
        ->when(isset($filters['user_id']),function ($query) use ($filters){
            $query->where('user_id',$filters['user_id']);
        })
        //فلتر التاريخ من
        ->when(isset($filters['from_date']),function ($query) use ($filters){
            $query->whereDate('created_at', '>=', $filters['from_date']);
        })
        //فلتر التاريخ الي
        ->when(isset($filters['to_date']),function ($query) use ($filters){
            $query->whereDate('created_at', '<=', $filters['to_date']);
        })


        //البحث الشامل
        ->when(isset($filters['search']), function ($query) use ($filters) {
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
            throw new \Exception('Order not found');
        }
        return $order;
    }

    /**
     * تعيين فني للطلب وتغيير حالته
     */
    public function assignOrderToTechnician(int $id, int $technicianId): Order
    {
         $order =$this->getOpenOrder($id);
         $order->assigned_to = $technicianId;
        $order->status = 'assigned';
        $order->save();

     return $order;
        // تأكد من حالة الطلب أولاً
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
     * ميثود مساعدة للتأكد إذا كان الطلب "مغلق" ولا يقبل التعديل
     */
    private function getOpenOrder(int $id): Order
    {
          $order = Order::find($id);
        if (!$order) {
            throw new \Exception('Order not found');
        }elseif ($order->status == 'completed' || $order->status == 'cancelled') {
            // أضفنا [] لأن الميثود في الـ Trait بتستقبل Array أولاً
            throw new \Exception('Cannot modify this order');        }
            return $order;
    }
}
