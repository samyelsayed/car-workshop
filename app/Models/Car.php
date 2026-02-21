<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plate_number',
        'brand',
        'model',
        'year',
        'color',
    ];

        // Relationships:

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    protected static function boot()
{
    parent::boot();

    static::deleting(function ($car) {
        $car->orders()->delete();  // Soft Delete
    });
}
}
