<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\updateProfile;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use App\Services\User\UserProfileService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{use ApiTrait;
//     public function view(Request $request){
//      $user = $request->user();
//      return $this->Data(compact('user'),'This is the user data');
//     }

//     public function update(updateProfile $request){
//     //  $user = User::findOrFail($request->id);
//      $user = $request->user();
//      $user->first_name = $request->first_name;
//      $user->last_name = $request->last_name;


//  if ($request->hasFile('image')) {

//     // حذف الصورة القديمة
//     $oldImage = $user->getRawOriginal('image');
//     if ($oldImage && file_exists(public_path('images/users/'.$oldImage)) && $oldImage != 'default.png') {
//     unlink(public_path('images/users/'.$oldImage));
// }

//     // رفع الصورة الجديدة
//     $image = $request->file('image');
//     $imageName = time().'.'.$image->getClientOriginalExtension();
//     $image->move(public_path('images/users'), $imageName);

//     // حفظ اسم الصورة في الداتابيز
//     $user->image = $imageName;

// }
// $user->save();
//      return $this->Data(compact('user'),'Active user data has been modified');
//     }





    protected $userProfileService;
        public function __construct( UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;

    }


    public function view(Request $request){
     $user = $request->user();
     return $this->Data(['user' => $user],'This is the user data');
    }


    public function update(updateProfile $request){
        $user = $request->user();
        $updatedUser = $this->userProfileService->update($user, $request->validated());

        return $this->Data(['user' => new UserResource($updatedUser)],'update user data has been modified');


    }






}
