<?php

namespace App\Http\Controllers\Api\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\WorkProgress\CreateWorkProgressRequest;
use App\Http\Requests\Api\Admin\WorkProgress\UpdateWorkProgressRequest;
use App\Http\Resources\Admin\WorkProgressResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\WorkProgressService;
use Illuminate\Http\Request;

class WorkProgressController extends Controller
{
    use ApiTrait;

    public function __construct(protected WorkProgressService $workProgressService ){}

    public function index(int $orderId)
    {
        $stages = $this->workProgressService->getWorkProgressByOrder($orderId);
        
        // ضفنا رسالة النجاح المترجمة هنا عشان توحيد شكل الريسبونس
        return $this->Data(WorkProgressResource::collection($stages), __('messages.work_stages_retrieved'));
    }

    public function store(CreateWorkProgressRequest $request)
    {
        $stage = $this->workProgressService->createWorkProgress($request->validated());
        return $this->Data(new WorkProgressResource($stage), __('messages.work_stage_created'), 201);
    }

    public function show(int $id)
    {
        $stage = $this->workProgressService->getWorkProgressById($id);
        return $this->Data(new WorkProgressResource($stage), __('messages.work_stage_details_retrieved'));
    }

    public function update(UpdateWorkProgressRequest $request, int $id)
    {
        $stage = $this->workProgressService->updateWorkProgress($id, $request->validated());
        return $this->Data(new WorkProgressResource($stage), __('messages.work_stage_updated'));
    }

    public function complete(int $id)
    {
        $stage = $this->workProgressService->completeWorkProgress($id);
        return $this->Data(new WorkProgressResource($stage), __('messages.work_stage_completed'));
    }
}