<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Traits\ApiTrait;
use App\Services\Workshop\WorkshopService;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    use ApiTrait;

    protected WorkshopService $workshopService;

    public function __construct(WorkshopService $workshopService)
    {
        $this->workshopService = $workshopService;
    }

    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $services = $this->workshopService->getActiveServices($searchTerm);

        if ($services->isEmpty()) {
            return $this->SuccessMessage(__('messages.no_services_found'), 200);
        }

        $transformedService = ServiceResource::collection($services);
        
        return $this->Data(['service' => $transformedService], __('messages.services_retrieved'));
    }

    public function show(Request $request, int $id)
    {
        $service = $this->workshopService->findActiveServiceById($id);
        $transformedService = new ServiceResource($service);

        return $this->Data(['service' => $transformedService], __('messages.service_details_retrieved'));
    }
}