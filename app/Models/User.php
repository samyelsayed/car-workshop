<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,SoftDeletes,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'image',
        'code',
        'code_expires_at',
        'code_purpose',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'code',
        'code_expires_at',
        'code_purpose',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'code_expires_at' => 'datetime',
        ];
    }

        // Relationships:

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function mobiles()
{
    return $this->hasMany( UserMobile::class);
}

        protected static function boot()
    {
        parent::boot();

        // لما User يتحذف (Soft Delete)
        static::deleting(function ($user) {
            // حذف كل العربيات (Soft Delete)
        $user->cars()->delete();
        // Hard Delete (واضح)
        $user->mobiles()->delete();
        $user->addresses()->delete();
        $user->notifications()->delete();
        });



        static::restoring(function ($user) {
        // بننادي على الحاجات اللي كانت ممسوحة بسببه ونرجعها
        $user->cars()->withTrashed()->restore();

    });
    }






protected function image(): Attribute
{
    return Attribute::make(
        get: function ($value) {
            if (empty($value)) {
                return asset('images/users/default.png');
            }
            return asset('images/users/' . $value);
        }
    );
}

}
