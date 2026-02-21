<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [

    	'user_id',
        'order_id',
        'type',
        'title',
        'message',
        'is_read',
                    ];
          protected function casts(): array{
            return [
           'is_read' => 'boolean',
            ];

          }
      // Relationships:

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}

