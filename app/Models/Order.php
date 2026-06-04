<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'invoice_no', 'tenant_id', 'outlet_id', 'user_id', 
        'total_amount', 'discount', 'net_amount', 'payment_method_id'
    ];
}