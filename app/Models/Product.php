<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'category_id', 'name', 'sku', 'cost_price', 'sale_price', 'status'];
}