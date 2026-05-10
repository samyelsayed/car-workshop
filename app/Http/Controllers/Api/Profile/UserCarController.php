<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Car\StoreCarRequest;
use App\Http\Requests\Api\User\Car\UpdateCarRequest;
use App\Http\Resources\UserCarResource;
use App\Http\Traits\ApiTrait;
use App\Services\User\UserCarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCarController extends Controller
{
    use ApiTrait;

    protected UserCarService $carService;

    public function __construct(UserCarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * Get all user cars
     */
    public function index(Request $request): JsonResponse
    {
        $cars = $this->carService->getUserCars($request->user());

        return $this->data(
            ['cars' => UserCarResource::collection($cars)],
            'Cars retrieved successfully'
        );
    }

      /**
     * Show car
     */
        public function show(int $id,Request $request): JsonResponse
        {
            $car = $this->carService->getCarById($request->user(), $id);

            return $this->data(
                ['car' => new UserCarResource($car)],
                'Car details retrieved successfully'
            );


    /**
     * Create new car
     */
    public function store(StoreCarRequest $request): JsonResponse
    {
        $car = $this->carService->createCar(
            $request->user(),
            $request->validated()
        );

        return $this->data(
            ['car' => new UserCarResource($car)],
            'Car added successfully',
            201
        );
    }

    /**
     * Update car
     */
    public function update(UpdateCarRequest $request, int $id): JsonResponse
    {
        $car = $this->carService->updateCar(
            $request->user(),
            $id,
            $request->validated()
        );

        return $this->data(
            ['car' => new UserCarResource($car)],
            'Car updated successfully'
        );
    }

    /**
     * Delete car
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->carService->deleteCar($request->user(), $id);

        return $this->successMessage('Car deleted successfully');
    }
}
