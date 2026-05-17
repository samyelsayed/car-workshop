<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\updateProfile; // يفضل لاحقاً تعديلها لتبدأ بحرف كابيتال UpdateProfile لتتبع المعايير
use App\Http\Resources\UserDetailedResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\UserProfileService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    use ApiTrait;

    protected UserProfileService $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function view(Request $request)
    {
        $user = $request->user();

        return $this->Data(
            ['user' => new UserDetailedResource($user)],
            __('messages.profile_retrieved')
        );
    }

    public function update(updateProfile $request)
    {
        $user = $request->user();
        $updatedUser = $this->userProfileService->update($user, $request->validated());

        return $this->Data(
            ['user' => new UserDetailedResource($updatedUser)],
            __('messages.profile_updated')
        );
    }
}
