<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Phone\PhoneRequest;
use App\Http\Resources\UserMobileResource;
use App\Http\Traits\ApiTrait;
use App\Models\UserMobile;
use App\Services\User\UserMobileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPhoneController extends Controller
{
    use ApiTrait;

// public function index(Request $request){
// $user = $request->user();
// // $mobiles = UserMobile::where('user_id',$user->id)->get();          //elqoant
// // $mobiles = DB::table('user_mobiles')->where('user_id',$user->id)->get();       //query builder
// $mobiles = $user->mobiles;
// return $this->Data(compact('mobiles'),'Data retrieved successfully');

// }


//     public function store(PhoneRequest $request){
//     $user = $request->user();
//     // $add_mobile = new UserMobile();
//     // $add_mobile->user_id =$user->id;
//     // $add_mobile->mobile_number =$request->phone;
//     // $add_mobile ->save();

//     $add_mobile =$user->mobiles()->create($request->validated());
//     return $this->Data(compact('add_mobile'),'Phone number added successfully');
//     }

//     public function destroy($id , Request $request){
//         $user = $request->user();
//         $userMobile =$user->mobiles()->find($id);
//         if (!$userMobile) {
//          return $this->ErrorMessage([], 'Phone not found', 404);
//         }
//         $userMobile->delete();
//         return $this->SuccessMessage('Phone number deleted successfully');
//     }


    protected $userMobileService;
public function __construct( UserMobileService $userMobileService)
    {
        $this->userMobileService = $userMobileService;

    }



    public function index(Request $request){
    $user = $request->user();
    $mobiles= $this->userMobileService->getMobiles($user);
     if ($mobiles->isEmpty()) {
        return $this->SuccessMessage('No mobiles found for this user yet, Add your first mobile');
      }
    return $this->Data(['mobiles' => UserMobileResource::collection($mobiles)], 'Data retrieved successfully');

    }



    public function store(PhoneRequest $request){
        $mobile =$this->userMobileService->createMobile($request->user(), $request->validated());
        return $this->Data(['mobiles' => new UserMobileResource($mobile)], 'Phone number added successfully');
    }


    public function destroy(Request $request, int $id){
        $this->userMobileService->deleteMobile($request->user(),$id);
        return $this->SuccessMessage('Phone number deleted successfully');
        }

}
