<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'category_id', 'price', 'stock', 'supplier_id'];
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_values')
                    ->withPivot('value')
                    ->withTimestamps();
    }
    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->withTimestamps();
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavorite()
    {
        return auth()->user() && $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function productViews()
    {
        return $this->hasMany(ProductView::class);
    }
    public function protype()
    {
        return $this->hasOne(Protype::class);
    }
    public function imgPros()
    {
        return $this->hasMany(ImgPro::class);
    }

}
