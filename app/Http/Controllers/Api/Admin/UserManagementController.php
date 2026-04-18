<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\User\AdminUpdateUserRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    use ApiTrait;

        public function index(Request $request){

        $query =User::withTrashed()->with([ 'user_mobiles']);

            //filters
            if($request->filled('role')){
                $query->where('role',$request->role);
            }

            if($request->filled('email_verified')){
                $query->whereNotNull('email_verified_at');
            }

            if($request->filled('deleted_at')){
                $query->whereNotNull('deleted_at');
            }


           //search


            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    // البحث في جدول الموبايلات (العلاقة)
                    ->orWhereHas('user_mobiles', function ($mobileQuery) use ($search) {
                        $mobileQuery->where('number', 'like', "%$search%");
                        // تأكد من اسم العمود في جدول الموبايلات (غالباً هو number أو phone)
                    });
                });
            }
             $users =$query->paginate(10);
           return $this->Data(AdminUserResource::collection($users), 'Users retrieved successfully');
           }

            public function show(Request $request , $id){
                $user =User::withTrashed()->with(['user_mobiles', 'addresses', 'cars', 'orders'])->findOrFail($id);
                if($user->trashed()){

                }
            }


            public function update(AdminUpdateUserRequest $request , $id){
                $user = User::where('id',$id)->firstOrFail();
                if($user->email !== $request->email){
                $user->email_verified_at =null;

            }
                $user->update($request->validated());

           return $this->SuccessMessage('User updated successfully',200);
            }


        public function destroy(Request $request , $id){
                $user = User::findOrFail($id);
                $user->delete()->save();


           return $this->SuccessMessage('User deleted successfully',200);
            }


        public function Restore (Request $request , $id){
                $user = User::onlyTrashed()->findOrFail($id);
                $user->restore();


           return $this->SuccessMessage('User restored successfully',200);
            }


        public function toggleBlock (Request $request , $id){
                $user = User::findOrFail($id);
                if($user->is_blocked == false && $request->blocked == true){
                    $user->is_blocked = true;
                    $message ='User blocked successfully';
                }else{
                    $user->is_blocked = false;
                    $message ='User unblocked successfully';
                }
                $user->save();

           return $this->SuccessMessage($message,200);
            }




        }





    //

