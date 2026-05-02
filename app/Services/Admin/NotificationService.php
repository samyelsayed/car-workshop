<?php

namespace App\Services\Admin;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function sendNotificationToUser(array $data): Notification
    {
        $user =User::find($data['user_id']);
        if(!$user){
            //arme el ekseption error
        }


        if(filled($data['order_id'])){
            $order = Order::find($data['order_id']);
            if(!$order){
                //arme el ekseption error
            }
        }

          return Notification::create([
            'user_id'  => $data['user_id'],
            'order_id' => $data['order_id'] ?? null,
            'type'     => $data['type'],
            'title'    => $data['title'],
            'message'  => $data['message'],
            'is_read'  => false,
          ]);
    }




public function broadcastNotification(array $data): void
{
    $now = now();

    User::query()
        ->when(!empty($data['user_role']), function ($query) use ($data) {
            $query->where('role', $data['user_role']);
        })
        ->chunk(1000, function ($users) use ($data, $now) {

            $notifications = $users->map(function ($user) use ($data, $now) {
                return [
                    'user_id'    => $user->id,
                    'order_id'   => null,
                    'type'       => $data['type'],
                    'title'      => $data['title'],
                    'message'    => $data['message'],
                    'is_read'    => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            Notification::insert($notifications);
        });
}
}
