<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',     // Keep this if you want
        'name',           // ← This matches your migration
        'price',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
