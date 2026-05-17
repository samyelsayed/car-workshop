<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\StoreAddressRequest;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Resources\UserAddressResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\UserAddressService;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    use ApiTrait;

    protected UserAddressService $addressService;

    public function __construct(UserAddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(Request $request)
    {
        $addresses = $this->addressService->getAddresses($request->user());

        if ($addresses->isEmpty()) {
            return $this->SuccessMessage(__('messages.no_addresses_found'));
        }

        return $this->Data(['addresses' => UserAddressResource::collection($addresses)], __('messages.addresses_retrieved'));
    }

    public function show(int $id, Request $request)
    {
        $addresse = $this->addressService->showAddresses($id, $request->user());

        return $this->Data(['address' => new UserAddressResource($addresse)], __('messages.address_details_retrieved'));
    }

    public function store(StoreAddressRequest $request)
    {
        $add_address = $this->addressService->createAddress($request->user(), $request->validated());

        return $this->Data(['address' => new UserAddressResource($add_address)], __('messages.address_created'));
    }

    public function update(UpdateAddressRequest $request, int $id)
    {
        $address = $this->addressService->updateAddress($request->user(), $id, $request->validated());

        return $this->Data(['address' => new UserAddressResource($address)], __('messages.address_updated'));
    }

    public function destroy(Request $request, int $id)
    {
        $this->addressService->deleteAddress($request->user(), $id);

        return $this->SuccessMessage(__('messages.address_deleted'));
    }
}
