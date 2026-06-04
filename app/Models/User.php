<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    // রোল রিলেশনশিপ
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