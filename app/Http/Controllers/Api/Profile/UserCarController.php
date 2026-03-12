<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserCarRequest;
use App\Http\Resources\UserCarResource;
use App\Http\Traits\ApiTrait;
use Illuminate\Http\Request;

class UserCarController extends Controller
{
     use ApiTrait;

      public function index(Request $request){
        $user = $request->user();
        $cars = $user->cars;
        if($cars->isEmpty()){
            return $this->SuccessMessage('No cars found for this user yet, Add your first car', 200);
        }
        $transformedCars = UserCarResource::collection($cars);
        return $this->Data(['cars' => $transformedCars],'Data retrieved successfully');
      }



      public function store(UserCarRequest $request){
        $user = $request->user();

        // $add_car =$user->cars()->create([
        //     'plate_number' => $request->plate_number,
        //     'brand'        => $request->brand,
        //     'model'        => $request->model,
        //     'year'         => $request->year,
        //     'color'        => $request->color,
        // ]);
        $add_car =$user->cars()->create($request->validated());
        $transformedCar = new UserCarResource($add_car);

        return $this->Data(['car' => $transformedCar], 'Car added successfully', 201);

      }




      public function update(UserCarRequest $request, $id){
        $user = $request->user();
        $car =$user->cars()->findOrFail($id);
        $car ->update($request->validated());

        $transformedCar = new UserCarResource($car);
        return $this->Data(['car' => $transformedCar], 'Car updated successfully', 200);
      }


        public function destroy(Request $request, $id){
        $user = $request->user();
        $car =$user->cars()->findOrFail($id);
        $car ->delete();

        return $this->SuccessMessage('Car deleted successfully');
      }

}
