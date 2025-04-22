<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id'];

    // علاقة مع نموذج المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع نموذج المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
