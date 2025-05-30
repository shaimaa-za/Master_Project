<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart'; 
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // علاقة السلة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة السلة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
