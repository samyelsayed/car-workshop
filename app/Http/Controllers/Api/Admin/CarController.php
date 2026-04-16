<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminCarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    use ApiTrait;

    protected $adminCarService;

    // حقن السيرفس في الكنترولر (Dependency Injection)
    public function __construct(AdminCarService $adminCarService)
    {
        $this->adminCarService = $adminCarService;
    }
    public function index(Request $request){

     $cars =$this->adminCarService->getAllCars($request->all());
     $carsData= UserResource::collection($cars)->response()->getData(true);

     return $this->Data($carsData,'cars retrieved successfully',200);

     }




     public function show( $id){
      $car = $this->adminCarService->getCarById($id);
       return $this->Data( new UserResource($car), 'Car details retrieved successfully', 201);
     }


    public function destroy(Request $request , $id){
        $this->adminCarService->deleteCar($id);

        return $this->SuccessMessage('Car deleted successfully');

     }


}
