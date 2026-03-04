<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{use ApiTrait;
    public function view(Request $request){
     $user = $request->user();
     return $this->Data(compact('user'),'This is the user data');
    }
}
