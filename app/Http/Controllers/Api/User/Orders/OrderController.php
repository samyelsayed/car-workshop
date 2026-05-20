<?php

namespace App\Http\Controllers\Api\User\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiTrait;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiTrait;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->user(), $request->validated());
        
        return $this->Data(new OrderResource($order), __('messages.order_created'), 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $this->orderService->getUserOrders($user);
        $data = OrderResource::collection($orders)->response()->getData(true);
        
        return $this->Data($data, __('messages.orders_retrieved'), 200);
    }

    public function edit(Request $request, int $id)
    {
        $order = $this->orderService->show($request->user(), $id);

        return $this->Data(new OrderResource($order), __('messages.order_edit_data_retrieved'));
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $user = $request->user();
        $order = $this->orderService->updateOrder($user, $id, $request->validated());
        
        return $this->Data(new OrderResource($order), __('messages.order_updated'), 200);
    }

    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        $this->orderService->deleteOrder($user, $id);
        
        return $this->SuccessMessage(__('messages.order_deleted'), 200);
    }
}