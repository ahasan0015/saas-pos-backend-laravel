<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // app/Models/User.php

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'tenant_id',
        'outlet_id'
    ];
    // role relationship
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // e
    public function hasRole(string $roleName): bool
    {
        $role = $this->role()->getResults();
        return $role !== null && $role->name === $roleName;
    }
}
