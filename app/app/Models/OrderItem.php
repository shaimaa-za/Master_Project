<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price','subtotal'];

    // علاقة العنصر بالطلب (كل عنصر مرتبط بطلب معين)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة العنصر بالمنتج (كل عنصر مرتبط بمنتج معين)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'product_id', 'product_id')->whereColumn('order_id', 'order_id');
    }
}
