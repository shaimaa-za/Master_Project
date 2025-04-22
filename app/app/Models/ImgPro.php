<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImgPro extends Model {
    use HasFactory;

    protected $table = 'imgpro'; 
    //protected $fillable = ['product_id', 'image_url', 'descriptors'];
    protected $fillable = [
        'product_id',
        'image_url',
        'faiss_id' 
    ];
    protected $casts = [
        'descriptors' => 'array', // تحويل الميزات إلى JSON
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
