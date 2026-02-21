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
}
