<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\StoreAddressRequest;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Traits\ApiTrait;
use App\Models\UserAddress;
use App\Services\User\UserAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAddressController extends Controller
{
    use ApiTrait;


    // public function index(Request $request){
    //    $user =$request->user();
    // //    $addresses = $user->addresses;
    // //    $addresses= UserAddress::where('user_id',$user->id)->get();
    //    $addresses =DB::table('user_addresses')->where('user_id',$user->id)->get();
    //    if ($addresses->isEmpty()) {
    //     return $this->SuccessMessage('No addresses found for this user yet, Add your first address');
    //     // أو تبعت الداتا فاضية مع رسالة مختلفة
    // }

    //    return $this->Data(compact('addresses'),'Data retrieved successfully');
    // }




    // public function store(StoreAddressRequest $request){
    //    $user =$request->user();
    // //    $add_address =$user->addresses()->create([
    // //         'address_type' =>$request->address_type,
    // //         'street' => $request->street ,
    // //         'city' => $request->city ,
    // //         'country' => $request->country ,
    // //         'is_default' => $request->is_default
    // //     ]);
    // $add_address = DB::transaction(function () use ($user, $request) {

    //     if ($request->is_default) {
    //         $user->addresses()->update(['is_default' => false]);
    //     }
    //     return $user->addresses()->create($request->validated());
    // });
    //      return $this->Data(compact('add_address'),'Address added successfully');
    // }




    // public function update(UpdateAddressRequest  $request, $id){
    //       $user =$request->user();
    //       $address =$user->addresses()->findOrFail($id);

    //        DB::transaction(function() use($user ,$address,$request ){
    //            if ($request->is_default) {
    //         $user->addresses()->where('id', '!=' , $address->id)->update(['is_default' => false]);
    //         }
    //       $address->update($request->validated());
    //       return $address;
    //       });

    //       return $this->Data(compact('address'), 'Address updated successfully');
    // }




    // public function destroy(Request $request, $id){
    //     $user = $request->user();
    //     $address = $user->addresses()->findOrFail($id);

    //     DB::transaction(function() use($user ,$address,$request ){
    //     if($address->is_default){
    //         $nextAddress = $user->addresses()->where('id', '!=' , $address->id)->first();
    //         if($nextAddress){
    //             $nextAddress->update(['is_default' => true]);
    //         }
    //     }
    //     $address->delete();
    //     });
    //    return $this->SuccessMessage('Address deleted successfully');
    // }




        protected $addressService;
    public function __construct( UserAddressService $addressService)
        {
            $this->addressService = $addressService;

      }



      public function index(Request $request){

      $addresses = $this->addressService->getAddresses($request->user());
      if ($addresses->isEmpty()) {
        return $this->SuccessMessage('No addresses found for this user yet, Add your first address');
      }
      return $this->Data(['addresses' => $addresses], 'Data retrieved successfully');

      }

    public function store(StoreAddressRequest $request){
     $add_address =$this->addressService->createAddress($request->user(), $request->validated());

       return $this->Data(['address' => $add_address], 'Address added successfully');

    }

public function update(UpdateAddressRequest $request, int $id){
    $address= $this->addressService->updateAddress($request->user(),$id, $request->validated());

 return $this->Data(['address' => $address], 'Address updated successfully');

}


public function destroy(Request $request, $id){
$this->addressService->deleteAddress($request->user(),$id);

return $this->SuccessMessage('Address deleted successfully');

}







}
