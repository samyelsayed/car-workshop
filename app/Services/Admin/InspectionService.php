<?php

namespace App\Services\Admin;

use App\Exceptions\Inspections\InspectionNotFoundException;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Models\Inspection;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    /**
     * Get all inspections for an order
     */
    public function getInspectionsByOrder(int $orderId): Collection
    {
        $inspections = Inspection::where('order_id', $orderId)->get();
        if ($inspections->isEmpty()) {
        throw new InspectionNotFoundException();
    }
        return $inspections;
    }

    /**
     * Create new inspection
     */
    public function createInspection(array $data): Inspection
    {
        $order = Order::find($data['order_id']);
        if(!$order){
        throw new OrderNotFoundException();
        }
        return DB::transaction(function () use ($data) {
        $inspection = Inspection::create($data);
        return $inspection;
    });
    }



    /**
     * Update inspection
     */
    public function updateInspection(int $id, array $data): Inspection
    {
        $inspection = $this->findInspectionById($id);
        $inspection->update($data);
        return $inspection;

    }

    /**
     * Delete inspection
     */
    public function deleteInspection(int $id): void
    {
         $inspection = $this->findInspectionById($id);
         $inspection->delete();

    }


    public function findInspectionById(int $id): Inspection
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            throw new InspectionNotFoundException();
        }

        return $inspection;
    }
}
