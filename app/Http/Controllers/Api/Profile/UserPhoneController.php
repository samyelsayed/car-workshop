<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Phone\PhoneRequest;
use App\Http\Resources\UserMobileResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\UserMobileService;
use Illuminate\Http\Request;

class UserPhoneController extends Controller
{
    use ApiTrait;

    protected UserMobileService $userMobileService;

    public function __construct(UserMobileService $userMobileService)
    {
        $this->userMobileService = $userMobileService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $mobiles = $this->userMobileService->getMobiles($user);

        if ($mobiles->isEmpty()) {
            return $this->SuccessMessage(__('messages.no_mobiles_found'));
        }

        return $this->Data(
            ['mobiles' => UserMobileResource::collection($mobiles)], 
            __('messages.mobiles_retrieved')
        );
    }

    public function show(int $id, Request $request)
    {
        $mobile = $this->userMobileService->getMobileById($request->user(), $id);
        
        return $this->Data(
            ['mobile' => new UserMobileResource($mobile)], 
            __('messages.mobile_details_retrieved')
        );
    }

    public function store(PhoneRequest $request)
    {
        $mobile = $this->userMobileService->createMobile($request->user(), $request->validated());
        
        return $this->Data(
            ['mobiles' => new UserMobileResource($mobile)], 
            __('messages.mobile_added')
        );
    }

    public function destroy(Request $request, int $id)
    {
        $this->userMobileService->deleteMobile($request->user(), $id);
        
        return $this->SuccessMessage(__('messages.mobile_deleted'));
    }
}