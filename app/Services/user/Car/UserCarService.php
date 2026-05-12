<?php

namespace App\Services\User;

use App\Exceptions\Car\CarNotFoundException;
use App\Exceptions\Car\CarNotOwnedByUserException;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserCarService
{
    /**
     * Get all user cars
     */
    public function getUserCars(User $user): Collection
    {
        return $user->cars;
    }

    /**
     * Create new car
     */
    public function createCar(User $user, array $data): Car
    {
        return $user->cars()->create($data);
    }

    /**
     * Update car
     */
    public function updateCar(User $user, int $carId, array $data): Car
    {
        $car = $this->findUserCarOrFail($carId, $user);

        $car->update($data);

        return $car->fresh();
    }

    /**
     * Delete car
     */
    public function deleteCar(User $user, int $carId): void
    {
        $car = $this->findUserCarOrFail($carId, $user);

        $car->delete();
    }


    //get car by id for user
public function getCarById(User $user, int $carId): Car
    {
         $car = $this->findUserCarOrFail($carId, $user);
         return $car;
    }


    /**
     * Find user car or fail
     */
    protected function findUserCarOrFail(int $carId, User $user): Car
    {
         // 1. Check if car exists
        $car = Car::find($carId);

        if (!$car) {
            throw new CarNotFoundException();
        }

        // 2. Check ownership
        if ($car->user_id !== $user->id) {
            throw new CarNotOwnedByUserException();
        }

        return $car;
    }
}
