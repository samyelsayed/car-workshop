<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
        use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'car_id',
        'pickup_required',
        'pickup_location',
        'pickup_datetime',
        'status',
        'total_cost',
    ];

        protected function casts(): array
    {
        return [
            'pickup_required' => 'boolean',
            'pickup_datetime' => 'datetime',
            'total_cost' => 'decimal:2',
        ];
    }

            // Relationships:

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

        public function workprogress()
    {
        return $this->hasMany(WorkProgress::class);
    }

        public function inspection()
    {
        return $this->hasMany(Inspection::class);
    }

        public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
