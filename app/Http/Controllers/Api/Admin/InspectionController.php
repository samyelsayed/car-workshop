<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Inspection\CreateInspectionRequest;
use App\Http\Requests\Api\Admin\Inspection\UpdateInspectionRequest;
use App\Http\Resources\Admin\AdminInspectionResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\InspectionService;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    use ApiTrait;

        protected $inspectionService;

        public function __construct(InspectionService $inspectionService)
        {
            $this->inspectionService = $inspectionService;
        }


            public function index(int $orderId)
        {
            $inspections = $this->inspectionService->getInspectionsByOrder($orderId);

            // بنحولها للـ Resource عشان تترتب وتتنسق
            return $this->Data(AdminInspectionResource::collection($inspections),'Inspections retrieved successfully');
        }


            public function store(CreateInspectionRequest $request)
        {
            $inspection= $this->inspectionService->createInspection($request->validated());
            return $this->Data(new AdminInspectionResource($inspection), 'Inspection created successfully', 201);

        }

            public function show(int $id)
        {
            $inspection = $this->inspectionService->findInspectionById($id);
            return $this->Data(new AdminInspectionResource($inspection), 'Inspection retrieved successfully');
        }

            public function update(UpdateInspectionRequest $request, int $id)
        {
            $inspection= $this->inspectionService->updateInspection($id,$request->validated());
                return $this->Data(new AdminInspectionResource($inspection), 'Inspection updated successfully', 200);

        }

            public function destroy(int $id)
        {
            $this->inspectionService->deleteInspection($id);

            return $this->successMessage('Inspection deleted successfully');
        }

}
