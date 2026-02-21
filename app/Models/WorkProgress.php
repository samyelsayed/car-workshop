<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkProgress extends Model
{
    use HasFactory;
    protected $fillable = [

        'order_id',
        'stage',
        'status',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array{
             return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
