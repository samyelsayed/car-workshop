<?php

namespace App\Services\Admin;
use App\Models\Car;
class AdminCarService

{

public function getAllCars(array $filters, int $perPage = 10)
{
    // بنطلع الـ search من الـ filters عشان يبقا عندنا parameter واحد
    $search = $filters['search'] ?? null;
    $status = $filters['status'] ?? null;

    $query = Car::query()->with(['user']);

        if ($status === 'trashed') {
            $query->onlyTrashed(); // المحذوف بس
        } elseif ($status === 'all') {
            $query->withTrashed(); // الكل
        }


      return $query
        // لو brand موجودة، نفذ الـ function دي
        ->when($filters['brand'] ?? null, function ($query, $brand) {
            $query->where('brand', $brand);
        })
        ->when($filters['year'] ?? null, function ($query, $year) {
            $query->where('year', $year);
        })
        ->when($filters['user_id'] ?? null, function ($query, $userId) {
            $query->where('user_id', $userId);
        })

          //search
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%$search%")
                  ->orWhere('model', 'like', "%$search%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                  });
            });
        })
        ->paginate($perPage);

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
