<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Order\AdminUpdateUserRequest;
use App\Http\Resources\Admin\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiTrait;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersManagement extends Controller
{
    use ApiTrait;

    public function index(Request $request)
    {
        $query = Order::with(['user', 'car']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search);
                $q->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                       ->orwhere('last_name', 'like', "%$search%");
                });
                $q->orWhereHas('car', function ($q3) use ($search) {
                    $q3->where('plate_number', 'like', "%$search%");
                });
            });
        }

        $orders = $query->paginate(10);

        return $this->Data(
            OrderResource::collection($orders)->response()->getData(true),
            'Orders retrieved successfully'
        );
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(['user', 'car', 'inspections', 'workProgress', 'orderItems.service'])->findOrFail($id);
        return $this->Data((new OrderDetailsResource($order))->toArray($request), 'Order details have been successfully retrieved');
    }

    public function assignOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'completed' || $order->status == 'cancelled') {
            // أضفنا [] لأن الميثود في الـ Trait بتستقبل Array أولاً
            return $this->ErrorMessage([], 'you cannot assign this order', 400);
        }

        $order->assigned_to = $request->input('technician_id');
        $order->status = 'assigned';
        $order->save();

        return $this->Data((new OrderDetailsResource($order))->toArray($request), 'Order has been assigned successfully');
    }

    public function updateStatus(AdminUpdateUserRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'completed' || $order->status == 'cancelled') {
            return $this->ErrorMessage([], 'you cannt updated this order', 400);
        }
        $order->status = $request->status;
        $order->save();
        return $this->Data((new OrderDetailsResource($order))->toArray($request), 'Order status has been successfully modified');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'completed') {
            return $this->ErrorMessage([], "you can't cancel this order", 400);
        }
        $order->status = 'cancelled';
        $order->save();
        return $this->Data((new OrderDetailsResource($order))->toArray($request), 'Order canceled successfully');
    }
}
