<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Http\Traits\ApiTrait;
use App\Http\Resources\UserResource;
use App\Services\Auth\UpdatePasswordService;
use Illuminate\Http\JsonResponse;

class UpdatePasswordController extends Controller
{
    use ApiTrait;

    protected UpdatePasswordService $updatePasswordService;

    public function __construct(UpdatePasswordService $updatePasswordService)
    {
        $this->updatePasswordService = $updatePasswordService;
    }

    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $token = $this->updatePasswordService->changePassword(
            $request->user(),
            $request->validated()
        );

        return $this->Data([
            'user' => new UserResource($request->user()->fresh()),
            'token' => $token
        ], __('messages.password_updated'));
    }
}