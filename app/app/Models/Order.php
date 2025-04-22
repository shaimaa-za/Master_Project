<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_price',
    ];
    // علاقة الطلب بالمستخدم (كل طلب مرتبط بمستخدم واحد)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الطلب بالعناصر (كل طلب يحتوي على عدة عناصر)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // علاقة الطلب بالدفع (كل طلب قد يكون له دفعة مرتبطة به)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function review()
    {
        return $this->hasOne(Review::class);
    }
    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }
}
