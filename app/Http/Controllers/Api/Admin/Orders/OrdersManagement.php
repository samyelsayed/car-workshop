<?php

namespace App\Http\Controllers\Api\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Order\UpdateStatusRequest;
use App\Http\Resources\Admin\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminOrderService;
use Illuminate\Http\Request;

class OrdersManagement extends Controller
{
    use ApiTrait;

    protected AdminOrderService $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getAllOrders($request->all());

        return $this->Data(
            OrderResource::collection($orders)->response()->getData(true),
            __('messages.orders_retrieved')
        ); 
    }

    public function show(int $id)
    {
        $order = $this->orderService->getOrderDetails($id);

        return $this->Data(new OrderDetailsResource($order), __('messages.order_details_retrieved'));
    }

    // public function assignOrder(Request $request, int $id)
    // {
    //     $order = $this->orderService->assignOrderToTechnician($id, $request->technician_id);

    //     return $this->Data(new OrderDetailsResource($order), __('messages.order_assigned'));
    // }

    public function updateStatus(UpdateStatusRequest $request, int $id)
    {
        $order = $this->orderService->updateOrderStatus($id, $request->status);

        return $this->Data(new OrderDetailsResource($order), __('messages.order_status_updated'));
    }

    public function cancelOrder(int $id)
    {
        $order = $this->orderService->cancelOrder($id);

        return $this->Data(new OrderDetailsResource($order), __('messages.order_canceled'));
    }
}