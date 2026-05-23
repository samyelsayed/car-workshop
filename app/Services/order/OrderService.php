<?php

namespace App\Services\Order;

use App\Exceptions\Address\AddressNotFoundException;
use App\Exceptions\Address\AddressNotOwnedByUserException;
use App\Exceptions\Car\CarNotFoundException;
use App\Exceptions\Car\CarNotOwnedByUserException;
use App\Exceptions\Service\ServiceInactiveException;
use App\Exceptions\Service\ServiceNotFoundException;
use App\Models\Car;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;

class OrderService
{


public function getUserOrders(User $user, int $perPage = 10): CursorPaginator
    {

       return $user->orders()->with(['items','car'])->latest()->cursorPaginate($perPage);


    }



    public function createOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {

            $car = $this->verifyUserCar($user, $data['car_id']);
            // 1. إنشاء الأوردر الأساسي
            $order = $this->storeOrder($user, $data);

            // 2. التحقق من العنوان في حالة طلب استلام من البيت
                if ($data['pickup_required'] ?? false) {
                    // بنبعت id العنوان والـ user نفسه للتأكد
                    $this->requestHomePickup($data['address_id'], $user);
                }

               // 2. Verify services (active + exist)
            $services = $this->verifyServices($data['service_ids']);

            // 2. جلب الخدمات وحساب التكلفة وإنشاء العناصر (اللوجيك الدسم)
            $totalCost = $this->processOrderItems($order, $data['services']);

            // 3. تحديث المجموع النهائي
            $order->update(['total_cost' => $totalCost]);

            return $order->load(['items', 'car']);
        });
    }

    protected function requestHomePickup(int $addressId, User $user): Address
{
    $address = Address::find($addressId);

    // 1. هل العنوان موجود؟
    if (!$address) {
        throw new AddressNotFoundException();
    }

    // 2. هل العنوان ده فعلاً يخص اليوزر اللي باعت الطلب؟
    if ($address->user_id !== $user->id) {
        throw new AddressNotOwnedByUserException();
    }

   return $address;
}


    public function updateOrder(User $user,int $orderId, array $data): Order
    {
        if (isset($data['car_id'])) {
            $this->verifyUserCar($user, $data['car_id']);
        }

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



    /**
     * Verify services exist and are active
     */
    protected function verifyServices(array $serviceIds)
    {
        $services = Service::whereIn('id', $serviceIds)->get();

        // Check if all services exist
        if ($services->count() !== count($serviceIds)) {
            throw new ServiceNotFoundException('One or more services not found');
        }

        // Check if all services are active
        $inactiveServices = $services->where('is_active', false);

        if ($inactiveServices->isNotEmpty()) {
            $names = $inactiveServices->pluck('name')->join(', ');
            throw new ServiceInactiveException("The following services are not available: {$names}");
        }

        return $services;
    }




/**
 * Verify car exists and owned by user
 */
protected function verifyUserCar(User $user, int $carId): Car
{
    $car = Car::find($carId);

    if (!$car) {
        throw new CarNotFoundException();
    }

    if ($car->user_id !== $user->id) {
        throw new CarNotOwnedByUserException();
    }

    return $car;
}






}
