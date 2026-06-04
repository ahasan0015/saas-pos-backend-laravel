<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 🌟 এটি যোগ করুন
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant, HasFactory; // 🌟 HasFactory যোগ করুন

    protected $fillable = ['tenant_id', 'category_id', 'name', 'sku', 'cost_price', 'sale_price', 'status'];
}