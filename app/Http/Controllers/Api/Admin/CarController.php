<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
     use ApiTrait;
     public function index(Request $request){

        $query =Car::withTrashed()->with([ 'user']);

        if($request->filled('brand')){
            $query->where('brand',$request->brand);
        }
        if($request->filled('year')){
            $query->where('year',$request->year);
        }
        if($request->filled('user_id')){
            $query->where('user_id',$request->user_id);
        }



        if($request->filled('search')){
            $search =$request->search;
            $query->where(function ($q) use($search){

         $q->where('plate_number','like', "%$search%")
            ->orWhere('model','like', "%$search%")
                ->orWhereHas('user', function ($q) use($search) {
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
            });
        }
        $cars =$query->paginate(10);
        return $this->Data($cars , 'Cars retrieved successfully');
     }




     public function show(Request $request , $id){
      $car =Car::withTrashed()
                ->with(['user','orders'=> function ($query) {$query->latest();}])
                ->findOrFail($id);
      return $this->Data($car , 'Car details retrieved successfully');
     }


    public function destroy(Request $request , $id){
      $car = Car::findOrfail($id);
      $car->delete();
        return $this->SuccessMessage('Car deleted successfully');

     }


}
