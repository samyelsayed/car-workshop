<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Car;
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
        $car = $user->cars()->find($carId);

        if (!$car) {
            throw new \Exception('Car not found', 404);
        }

        return $car;
    }
}
