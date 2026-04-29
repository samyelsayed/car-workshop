<?php

namespace App\Services\Admin;

use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\WorkProgress\WorkProgressNotFoundException;
use App\Models\Order;
use App\Models\WorkProgress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WorkProgressService
{
    /**
     * Get all work progress stages for an order
     */
    public function getWorkProgressByOrder(int $orderId): Collection
    {
              $workProgress = WorkProgress::where('order_id', $orderId)->get();
        if ($workProgress->isEmpty()) {
        throw new WorkProgressNotFoundException();
    }
        return $workProgress;

    }

    /**
     * Create new work progress stage
     */
    public function createWorkProgress(array $data): WorkProgress
    {
        // Verify order exists
         $order = Order::find($data['order_id']);
        if(!$order){
        throw new OrderNotFoundException();
        }

        return WorkProgress::create([
            'order_id'=>$data['order_id'],
             'stage' => $data['stage'],
            'status' => $data['status'] ?? 'not_started',
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);


    }

    /**
     * Update work progress stage
     */
    public function updateWorkProgress(int $id, array $data): WorkProgress
    {
        $workProgress = $this->getWorkProgressById($id);
        // 1. تحديث البيانات المرسلة
        $workProgress->update($data);

       // 2. التحقق الذكي: لو الحالة اتغيرت لـ completed
        if (isset($data['status']) && $data['status'] === 'completed') {
            $workProgress->update([
                'completed_at' => now(),
                'started_at' => $workProgress->started_at ?? now(),
            ]);

            // 3. نده ميثود التحقق من حالة الأوردر بالكامل
            $this->checkAndUpdateOrderStatus($workProgress->order_id);
        }

       return $workProgress->fresh();

    }

     public function getWorkProgressById($id):WorkProgress
    { $workProgress =WorkProgress::find($id);
    if(!$workProgress ){
throw new WorkProgressNotFoundException();
    }
    return $workProgress;
    }

    /**
     * Mark work progress stage as completed
     */
    public function completeWorkProgress(int $id): WorkProgress
    {
        return DB::transaction(function () use ($id) {
         $workProgress = $this->getWorkProgressById($id);
         $workProgress->update([ 'status' => 'completed',
                'completed_at' => now(),
                'started_at' => $workProgress->started_at ?? now(),]);
            $this->checkAndUpdateOrderStatus($workProgress->order_id);

            return $workProgress->fresh();
        });

    }

    /**
     * Check if all work stages completed and update order
     */
    protected function checkAndUpdateOrderStatus(int $orderId): void
    {
        // Check if all stages are completed
        $hasIncomplete = WorkProgress::where('order_id', $orderId)
            ->where('status', '!=', 'completed')
            ->exists();

        // If all completed, update order status
        if (!$hasIncomplete) {
            Order::where('id', $orderId)->update(['status' => 'completed']);
        }
    }





}
