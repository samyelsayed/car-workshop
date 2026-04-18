<?php



namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Order\AdminUpdateUserRequest;
use App\Http\Resources\Admin\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminOrderService;
use Illuminate\Http\Request;

class OrdersManagement extends Controller
{
    use ApiTrait;

    protected $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService = $orderService;
    } 
    
    public function index(Request $request)
    {
        $orders = $this->orderService->getAllOrders($request->all());
        
        return $this->Data(
            OrderResource::collection($orders)->response()->getData(true),
            'Orders retrieved successfully'
        );
    }

    public function show($id)
    {
        $order = $this->orderService->getOrderDetails($id);
        
        return $this->Data(new OrderDetailsResource($order), 'Order details retrieved successfully');
    }


    public function assignOrder(Request $request, $id)
    {
        $order = $this->orderService->assignOrderToTechnician($id, $request->technician_id);
        
        return $this->Data(new OrderDetailsResource($order), 'Order assigned successfully');
    }

public function updateStatus(AdminUpdateUserRequest $request, $id)
    {
        $order = $this->orderService->updateOrderStatus($id, $request->status);
        
        return $this->Data(new OrderDetailsResource($order), 'Order status updated successfully');
    }



public function cancelOrder($id)
    {
        $order = $this->orderService->cancelOrder($id);
        
        return $this->Data(new OrderDetailsResource($order), 'Order canceled successfully');
    }

    
    }