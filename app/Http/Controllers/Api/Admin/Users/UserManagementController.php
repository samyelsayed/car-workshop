<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\User\AdminUpdateUserRequest;
use App\Http\Resources\Api\Admin\Users\AdminUserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    use ApiTrait;

    // حقن السيرفس في الكنترولر عشان نستخدمها في كل الميثودز
    public function __construct(protected AdminUserService $userService) {}

    public function index(Request $request)
    {
        // بنبعت كل الـ request للسيرفس وهي تتصرف في الفلترة
        $users = $this->userService->getAllUsers($request->all(), $request->per_page ?? 10);

        return $this->Data(
            AdminUserResource::collection($users)->resource,
            __('messages.users_retrieved')
        );
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);

        return $this->Data(new AdminUserResource($user), __('messages.user_details_retrieved'));
    }

    public function update(AdminUpdateUserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return $this->SuccessMessage(__('messages.profile_updated'), 200);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return $this->SuccessMessage(__('messages.user_deleted'), 200);
    }

    public function Restore($id)
    {
        $this->userService->restoreUser($id);

        return $this->SuccessMessage(__('messages.user_restored'), 200);
    }

    public function toggleBlock($id)
    {
        $user = $this->userService->toggleUserBlock($id);

        // ربط ديناميكي بالـ Key المناسب بناءً على حالة الحظر
        $key = 'messages.user_' . ($user->is_blocked ? 'blocked' : 'unblocked');

        return $this->SuccessMessage(__($key), 200);
    }
}
