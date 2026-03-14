<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Traits\ApiTrait;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{use ApiTrait;



        // $transformedCars = UserCarResource::collection($cars);
        // return $this->Data(['cars' => $transformedCars],'Data retrieved successfully');

     public function index(Request $request){
        $services = Service::where('is_active', true)->get();
         if($services->isEmpty()){
          return $this->SuccessMessage('No services found yet, Please check back later', 200);
         }

         $transformedServices =ServiceResource::collection($services);
         return $this->Data(['services'=>$transformedServices],'Services retrieved successfully');
    }



         public function show(Request $request, $id){
        $service = Service::where('is_active', true)->find($id);
         if(!$service){
          return $this->ErrorMessage(['service' => 'Service not found'],'Not Found',  404);
         }

         $transformedService =new ServiceResource($service);
         return $this->Data(['service'=>$transformedService],'Services retrieved successfully');
    }
}
