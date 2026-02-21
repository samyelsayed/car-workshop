<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspection extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'type',
        'inspection_date',
        'findings',
        'estimated_cost',
        'notes',
    ];


    protected function casts(): array{

     return [
            'inspection_date' => 'datetime',
            'estimated_cost' => 'decimal:2',
        ];
    }

                // Relationships:

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
