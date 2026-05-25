<?php

namespace App\Http\Controllers\Api\Admin\Cars;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\Cars\AdminCarResource;
use App\Http\Resources\Api\User\Profile\UserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminCarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    use ApiTrait;

    protected AdminCarService $adminCarService;

    // حقن السيرفس في الكنترولر (Dependency Injection)
    public function __construct(AdminCarService $adminCarService)
    {
        $this->adminCarService = $adminCarService;
    }

    public function index(Request $request)
    {
        $cars = $this->adminCarService->getAllCars($request->all());
        $carsData = AdminCarResource::collection($cars)->response()->getData(true);

        return $this->Data($carsData, __('messages.cars_retrieved'), 200);
    }

    public function show(int $id)
    {
        $car = $this->adminCarService->getCarById($id);
        return $this->Data(new AdminCarResource($car), __('messages.car_details_retrieved'), 200); // تعديل الـ Status Code لـ 200 لأنه عرض بيانات مش إنشاء
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminCarService->deleteCar($id);
        return $this->SuccessMessage(__('messages.car_deleted'));
    }

    public function restore(Request $request, int $id)
    {
        $car = $this->adminCarService->restoreCar($id);
        return $this->Data(new UserResource($car), __('messages.car_restored'), 200);
    }
}
