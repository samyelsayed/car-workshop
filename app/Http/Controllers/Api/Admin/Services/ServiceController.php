<?php

namespace App\Http\Controllers\Api\Admin\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Service\ServiceRequest;
use App\Http\Requests\Api\Admin\Service\UpdateServiceRequest;
use App\Http\Resources\Api\Admin\Services\ServiceResource;
use App\Http\Traits\ApiTrait;
use App\Models\Service;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use ApiTrait;

    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        $services = $this->adminService->getAllServices($request->all());
        $resourceCollection = ServiceResource::collection($services);

        return $this->Data($resourceCollection, __('messages.services_retrieved'), 200);
    }

    public function store(ServiceRequest $request)
    {
        $service = $this->adminService->storeService($request->validated());

        return $this->Data(new ServiceResource($service), __('messages.service_created'), 201);
    }

    public function show(int $id)
    {
        $service = $this->adminService->getServiceById($id);

        return $this->Data(new ServiceResource($service), __('messages.service_details_retrieved'), 200);
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $service = $this->adminService->updateService($id, $request->validated());

        return $this->Data(new ServiceResource($service), __('messages.service_updated'), 200);
    }

    public function destroy($id)
    {
        $this->adminService->deleteService($id);

        return $this->SuccessMessage(__('messages.service_deleted'), 200);
    }

    public function toggleStatus($id)
    {
        $service = $this->adminService->toggleServiceStatus($id);

        // ربط ديناميكي بالـ Key المناسب بناءً على حالة الـ active
        $key = 'messages.service_' . ($service->is_active ? 'activated' : 'deactivated');

        return $this->Data(new ServiceResource($service), __($key), 200);
    }

    public function restore($id)
    {
        $service = $this->adminService->restoreService($id);

        return $this->Data(new ServiceResource($service), __('messages.service_restored'), 200);
    }
}
