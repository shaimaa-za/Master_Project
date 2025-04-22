<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name']; // السماح بتهيئة الحقل name
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_values')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
