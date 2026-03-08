<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    /** @use HasFactory<\Database\Factories\UserAddressFactory> */
    use HasFactory;


    /**
     * الحقول المسموح بتعبئتها (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'address_type',
        'street',
        'city',
        'country',
        'is_default', // ضيفته لك عشان لو قررت تستخدمه لاحقاً
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    protected $casts = [
    'is_default' => 'boolean',
];
}
