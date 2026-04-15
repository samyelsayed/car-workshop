<?php
namespace App\Services\Admin;

use App\Models\Car;

class AdminCarService
{

public function getAllCars(array $filters, $search, int $perPage = 10)
    {
     $query =Car::withTrashed()->with([ 'user']);
             if(!empty($filters['brand'])){
            $query->where('brand',$filters['brand']);
        }
        if(!empty($filters['year'])){
            $query->where('year',$filters['year']);
        }
        if(!empty($filters['user_id'])){
            $query->where('user_id',$filters['user_id']);
        }

        if(!empty($search)){
         
            $query->where(function ($q) use($search){

         $q->where('plate_number','like', "%$search%")
            ->orWhere('model','like', "%$search%")
                ->orWhereHas('user', function ($q) use($search) {
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
            });
        }

        return $cars =$query->paginate($perPage);

    }


public function getCarById(int $id)
    {
              $car =Car::withTrashed()
                ->with(['user','orders'=> function ($query) {$query->latest();}])->find($id);
                if (!$car) {
                    throw new \Exception('Car not found');
                }
                return $car;

    }



    public function deleteCar(int $id)
    {
    $car = Car::find($id);
        if (!$car) {
            throw new \Exception('Car not found');
        }
      $car->delete();
    }
}