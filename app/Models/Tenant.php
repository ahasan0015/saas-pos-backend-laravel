<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable =
    ['business_name', 'owner_name', 'email', 'phone', 'tenant_status_id'];
}
