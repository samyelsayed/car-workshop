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

        protected static function boot()
    {
        parent::boot();

        // لما User يتحذف (Soft Delete)
        static::deleting(function ($user) {
            // حذف كل العربيات (Soft Delete)
            $user->cars()->delete();

            // حذف كل الإشعارات (Hard Delete)
            $user->notifications()->forceDelete();
        });
    }



    protected function image(): Attribute
{
    return Attribute::make(
        get: function ($value) {
            // 1. لو الخانة في الداتابيز فاضية أو نال
            if (empty($value)) {
                return asset('assets/defaults/default-user.png'); // مسار صورتك الافتراضية
            }

            // 2. لو الصورة عبارة عن رابط خارجي (لو بتستخدم Social Login مثلاً)
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            // 3. لو الصورة مرفوعة عندك على السيرفر
            return asset('storage/' . $value);
        },
    );
}
}
