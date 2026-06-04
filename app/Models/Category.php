<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use BelongsToTenant; // 🌟 সিকিউরিটি অ্যাক্টিভেটেড

    protected $fillable = ['tenant_id', 'name'];
}