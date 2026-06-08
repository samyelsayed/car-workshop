<?php

namespace App\Http\Controllers\Api\User\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\Profile\UserResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\UserProfileService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\User\UpdateProfileRequest;

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
            ['user' => new UserResource($user)],
            __('messages.profile_retrieved')
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $updatedUser = $this->userProfileService->update($user, $request->validated());

        return $this->Data(
            ['user' => new UserResource($updatedUser)],
            __('messages.profile_updated')
        );
    }

    public function deleteImage(Request $request)
    {
        $user = $request->user();
        
        // استدعاء السيرفس لحذف الملف وتحديث الداتابيز
        $updatedUser = $this->userProfileService->removeProfileImage($user);

        // إرجاع البيانات المحدثة بالـ Resource الموحد
        return $this->Data(
            ['user' => new UserResource($updatedUser)],
            __('messages.profile_image_removed')
        );
    }





}
