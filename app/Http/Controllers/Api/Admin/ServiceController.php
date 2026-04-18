<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Service\ServiceRequest;
use App\Http\Requests\Api\Admin\Service\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Http\Traits\ApiTrait;
use App\Models\Service;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use ApiTrait;

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }


    public function index(Request $request){

    $services =$this->adminService->getAllServices($request->all());
      $resourceCollection = ServiceResource::collection($services);
      return $this->Data($resourceCollection, 'Services retrieved successfully', 200);
    }


    public function store(ServiceRequest $request){

    $service = $this->adminService->storeService($request->validated());

        return $this->Data(new ServiceResource($service), 'Service Added Successfully', 201);

    }

public function show($id)
    {
        $service = $this->adminService->getServiceById($id);

        return $this->Data(new ServiceResource($service), 'Service details retrieved successfully', 200);
    }

      public function update(UpdateServiceRequest $request, $id){

        $service = $this->adminService->updateService($id, $request->validated());
        return $this->Data(new ServiceResource($service), 'Service Updated Successfully', 200);
    }

 public function destroy($id){

           $this->adminService->deleteService($id);
        return $this->SuccessMessage('Service Deleted Successfully', 200);
}


public function toggleStatus($id)
{
    $service = $this->adminService->toggleServiceStatus($id);

    $statusMessage = $service->is_active ? 'Activated' : 'Deactivated';

    return $this->Data(new ServiceResource($service),"Service $statusMessage Successfully",200);
}

}
