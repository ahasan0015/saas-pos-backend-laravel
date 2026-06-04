<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantStatus extends Model
{
    protected $fillable = ['slug', 'name'];
}
