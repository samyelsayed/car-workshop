<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\User\AdminUpdateUserRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    use ApiTrait;

    // حقن السيرفس في الكنترولر عشان نستخدمها في كل الميثودز
    public function __construct(protected AdminUserService $userService) {

    }


    public function index(Request $request)
    {
        // بنبعت كل الـ request للسيرفس وهي تتصرف في الفلترة
        $users = $this->userService->getAllUsers($request->all(), $request->per_page ?? 10);

        return $this->Data(
            AdminUserResource::collection($users)->resource,
            'Users retrieved successfully'
        );
    }


    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        return $this->Data(new AdminUserResource($user), 'User details retrieved successfully');
    }

    public function update(AdminUpdateUserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return $this->SuccessMessage('User updated successfully', 200);
    }


    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return $this->SuccessMessage('User deleted successfully', 200);
    }


    public function Restore($id)
    {
        $this->userService->restoreUser($id);

        return $this->SuccessMessage('User restored successfully', 200);
    }


    public function toggleBlock($id)
    {
        $user = $this->userService->toggleUserBlock($id);

        $message = $user->is_blocked ? 'User blocked successfully' : 'User unblocked successfully';

        return $this->SuccessMessage($message, 200);
    }
}
