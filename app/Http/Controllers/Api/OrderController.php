<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\ApiTrait;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  use ApiTrait;

    protected $orderService;
          public function __construct( OrderService $orderService)
        {
            $this->orderService = $orderService;

    }

public function create(StoreOrderRequest $request ){
    $order = $this->orderService->createOrder( $request->user(),$request->validated());
      return $this->Data( new OrderResource($order), 'Order created successfully', 201);

}

public function index(Request $request ){
    $user = $request->user();
    $orders = $this->orderService->getUserOrders($user);
    $data = OrderResource::collection($orders)->response()->getData(true);
    return $this->Data($data,'Order retrieved successfully',200);
}


public function edit(Request $request, $id) {
    $order = $this->orderService->show($request->user(), $id);
    
    return $this->Data(new OrderResource($order), 'Order data for editing retrieved');
}



public function update(UpdateOrderRequest $request, $id) {
    $user = $request->user();
    $order = $this->orderService->updateOrder($user,$id,$request->validated());
      return $this->Data( new OrderResource($order), 'Order updated successfully', 200);


}

public function destroy(Request $request, $id) {
    $user = $request->user();
      $order = $this->orderService->deleteOrder($user,$id);
      return $this->SuccessMessage('Order deleted successfully',200);

}

//   public function create(OrderRequest $request ){
//     return DB::transaction(function () use ($request) {

//         $services =Service::whereIn('id',$request->services)->get();

//             $user = $request->user();
//             $order = Order::create([
//                 'user_id'=>$user->id ,
//                 'car_id'=> $request->car_id,
//                 'pickup_location'=>$request->pickup_location??null,
//                 'pickup_datetime'=>$request->pickup_datetime??null,
//                 'status'=> 'pending',
//                 'total_cost' => 0,
//             ]);

//                 $total_cost = 0;
//             foreach($services as $service){
//                     $OrderItems =OrderItem::create([
//                         'order_id'=>$order->id,
//                         'service_id'    => $service->id,
//                         'service_name'  => $service->name, // Snapshot
//                         'service_image' => $service->image, // Snapshot
//                         'unit_price'    => $service->price, // Snapshot
//                         'quantity' => 1,
//                         'subtotal'=>$service->price * 1
//             ]);
//             $total_cost +=$service->price;
//             }
//             $order->update(['total_cost'=> $total_cost]);
//             $order->load(['items', 'car']); // اشحن الداتا كلها مرة واحدة
//             return $this->Data($order, 'Order created successfully', 201);
//         });
//   }


//   public function index(Request $request ){
//     $user = $request->user();
//     // $orders = Order::where('user_id',$user->id);
//     // $orders = Order::where('user_id',$user->id)->with(['items','car'])->latest()->paginate(10);
//     $orders = $user->orders()->with(['items','car'])->latest()->paginate(10);

//     return $this->Data($orders, 'Orders retrieved successfully', 200);
//     return $this->Data(OrderResource::collection($orders)->response()->getData(true), 'Success');

//   }


// public function update(OrderRequest $request, $id) {
//     $user = $request->user();

//     // 1. جلب الأوردر (تصحيح fital -> fail)
//     // $order = Order::where('id', $id)->where('user_id', $user->id)->firstOrFail();
//     $order = $user->orders()->findOrFail($id);
//     // 2. التحقق من الحالة (تصحيح seatus و العلامة)
//     if ($order->status !== 'pending') {
//         return $this->ErrorMessage('You cannot update the order after it has started', 403);
//     }

//     return DB::transaction(function() use ($request, $order) {
//         // 3. تحديث البيانات الأساسية
//         $order->update([
//             'car_id'          => $request->car_id,
//             'pickup_location' => $request->pickup_location ?? $order->pickup_location,
//             'pickup_datetime' => $request->pickup_datetime ?? $order->pickup_datetime,
//         ]);

//         if ($request->has('services') && is_array($request->services) && count($request->services) > 0) {
//         // 4. حذف الأصناف القديمة (تصحيح Items -> items)
//         $order->items()->delete();

//         // 5. إعادة بناء الأصناف وحساب التكلفة
//         $services = Service::whereIn('id', $request->services)->get();
//         $total_cost = 0;

//         foreach ($services as $service) {
//             $order->items()->create([
//                 'service_id'    => $service->id,
//                 'service_name'  => $service->name,
//                 'service_image' => $service->image,
//                 'unit_price'    => $service->price,
//                 'quantity'      => 1,
//                 'subtotal'      => $service->price * 1
//             ]);
//             $total_cost += $service->price;
//         }

//         // 6. تحديث المجموع النهائي والرد
//         $order->update(['total_cost' => $total_cost]);
//         }
//        $data = new OrderResource($order->load(['items', 'car']));
//         return $this->Data($data , 'Order updated successfully');
//     });
// }

// public function destroy(Request $request, $id) {
//     $user = $request->user();

// $order = $user->orders()->findOrFail($id);

//   if($order->status !== 'pending'){
//     return $this->ErrorMessage('You cannot delete the order after it has started', 403);
// }

//  $order->delete();
// return $this->SuccessMessage('Order deleted successfully',200);

// }


}
