<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 🌟 Sanctum টোকেনের জন্য ইমপোর্ট

class User extends Authenticatable
{
    // 🌟 HasApiTokens এবং বাকি ট্রেইটগুলো এখানে অ্যাক্টিভেট করা হলো
    use HasApiTokens, HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',  // 🏢 কোম্পানি ট্র্যাকিংয়ের জন্য
        'outlet_id',  // 🏪 নির্দিষ্ট শপ ট্র্যাকিংয়ের জন্য
        'name',
        'email',
        'phone',      // 📱 আমাদের লগইন ফিল্ড
        'password',
        'role',       // 🔑 admin অথবা cashier
        'status',     // 🟢 active/deactive
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }
}