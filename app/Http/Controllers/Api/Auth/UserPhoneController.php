<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PhoneRequest;
use App\Http\Traits\ApiTrait;
use App\Models\UserMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPhoneController extends Controller
{
    use ApiTrait;

public function index(Request $request){
$user = $request->user();
// $phones = UserMobile::where('user_id',$user->id)->get();          //elqoant
// $phones = DB::table('user_mobiles')->where('user_id',$user->id)->get();       //query builder
$phones = $user->phones;
return $this->Data(compact('phones'),'Data retrieved successfully');

}


public function store(PhoneRequest $request){
$user = $request->user();
// $add_phone = new UserMobile();
// $add_phone->user_id =$user->id;
// $add_phone->mobile_number =$request->phone;
// $add_phone ->save();

$add_phone =$user->phones()->create(['mobile_number'=>$request->phone]);
return $this->SuccessMessage('Phone number added successfully');
}

public function (PhoneRequest $request){
$user = $request->user();
}
