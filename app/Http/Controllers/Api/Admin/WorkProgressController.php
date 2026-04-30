<?php

namespace App\Http\Controllers\Api\Admin;

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

    public function index(int $orderId){
        $stages = $this->workProgressService->getWorkProgressByOrder($orderId);
        return $this->Data(WorkProgressResource::collection($stages));
    }



    public function store(CreateWorkProgressRequest $request)
    {
        $stage = $this->workProgressService->createWorkProgress($request->validated());
        return $this->Data(new WorkProgressResource($stage), 'Work stage created successfully', 201);
    }


    public function show(int $id)
    {
        $stage = $this->workProgressService->getWorkProgressById($id);
        return $this->Data(new WorkProgressResource($stage), 'Work stage details retrieved');
    }

    public function update(UpdateWorkProgressRequest $request, int $id)
    {
        $stage = $this->workProgressService->updateWorkProgress($id, $request->validated());
        return $this->Data(new WorkProgressResource($stage), 'Work stage updated successfully');
    }


    public function complete(int $id)
    {
        $stage = $this->workProgressService->completeWorkProgress($id);
        return $this->Data(new WorkProgressResource($stage), 'Work stage marked as completed');
    }

}
