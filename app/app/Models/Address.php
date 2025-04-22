<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'full_name', 'phone', 'address_line1', 'address_line2', 'city', 'state', 'country', 'zip_code', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }

}
