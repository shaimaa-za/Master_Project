<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARProduct extends Model
{
    use HasFactory;
    protected $table = 'productsAR';

    protected $fillable = ['name', 'description', 'price', 'image_url', 'model_url'];
}
