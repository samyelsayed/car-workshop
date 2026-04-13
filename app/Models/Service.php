<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
      use HasFactory, SoftDeletes;

      protected $fillable = [
        'name',
        'description',
        'image',
        'base_price',
        'is_active',
    ];

        protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

            // Relationships:

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }




protected function image(): Attribute
{
    return Attribute::make(
        get: function ($value) {
            if (empty($value)) {
                return asset('images/services/default.png');
            }
            return asset('images/services/' . $value);
        }
    );
}

public function scopeActive($query) {
        return $query->where('is_active', true);
    }

}
