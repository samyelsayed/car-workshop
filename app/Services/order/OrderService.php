<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{


public function getUserOrders(User $user, int $perPage = 10): LengthAwarePaginator
    {

       return $user->orders()->with(['items','car'])->latest()->paginate($perPage);


    }



    public function createOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. إنشاء الأوردر الأساسي
            $order = $this->storeOrder($user, $data);

            // 2. جلب الخدمات وحساب التكلفة وإنشاء العناصر (اللوجيك الدسم)
            $totalCost = $this->processOrderItems($order, $data['services']);

            // 3. تحديث المجموع النهائي
            $order->update(['total_cost' => $totalCost]);

            return $order->load(['items', 'car']);
        });
    }


    public function updateOrder(User $user, $orderId, array $data): Order
    {
        // 1. التأكد أن الأوردر قابل للتعديل (Pending)
        $order = $this->getOrderById($orderId, $user);
        $this->ensureOrderIsPending($order);

        return DB::transaction(function () use ($order, $data) {
            // 2. تحديث البيانات الأساسية
            $order->update([
                'car_id'          => $data['car_id'] ?? $order->car_id,
                'pickup_location' => $data['pickup_location'] ?? $order->pickup_location,
                'pickup_datetime' => $data['pickup_datetime'] ?? $order->pickup_datetime,
            ]);

            // 3. تحديث الخدمات لو مبعوثة
            if (isset($data['services']) && is_array($data['services'])) {
                $order->items()->delete(); // مسح القديم
                $totalCost = $this->processOrderItems($order, $data['services']);
                $order->update(['total_cost' => $totalCost]);
            }

            return $order->load(['items', 'car']);
        });
    }

    private function storeOrder(User $user, array $data): Order
    {

        return Order::create([
            'user_id'         => $user->id,
            'car_id'          => $data['car_id'],
            'pickup_location' => $data['pickup_location'] ?? null,
            'pickup_datetime' => $data['pickup_datetime'] ?? null,
            'status'          => 'pending',
            'total_cost'      => 0,
        ]);
    }


    public function show(User $user,int $orderId ): Order
    {
        $order = $this->getOrderById($orderId, $user);
        $this->ensureOrderIsPending($order);
        return $order->load(['items', 'car']);
    }


    public function deleteOrder(User $user,int $orderId ){
             $order = $this->getOrderById($orderId,$user);
            $this->ensureOrderIsPending($order);

    }

    private function processOrderItems(Order $order, array $serviceIds): float
    {

        $services = Service::whereIn('id', $serviceIds)->get();
                $total = 0;

                foreach ($services as $service) {
                    $order->items()->create([
                        'service_id'    => $service->id,
                        'service_name'  => $service->name,
                        'service_image' => $service->image,
                        'unit_price'    => $service->price,
                        'quantity'      => 1,
                        'subtotal'      => $service->price,
                    ]);
                    $total += $service->price;
                }
        return $total;

    }



private function getOrderById(int $orderId, User $user): Order
    {
         $order =$user->orders()->find($orderId);
        if (!$order) {
            throw new \Exception('Order not found');
        }
        return $order;
    }

    private function ensureOrderIsPending(Order $order): void
    {
        if ($order->status !== 'pending') {
            throw new \Exception('You cannot modify the order after it has started');
        }
    }

}