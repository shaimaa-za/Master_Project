<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'company_shipping',  // اسم شركة الشحن
        'shipping_fee',
        'tracking_number',
        'status',
        'address_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
