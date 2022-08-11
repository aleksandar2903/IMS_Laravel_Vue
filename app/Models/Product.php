<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'stock_defective', 'image_id', 'product_brand_id', 'product_subcategory_id'
    ];

    protected $appends = ['rating', 'rating_count'];

    public function category()
    {
        return $this->belongsTo('App\Models\ProductSubcategory', 'product_subcategory_id');
    }

    public function sub_category()
    {
        return $this->hasOneThrough('App\Models\ProductCategory', 'App\Models\ProductSubcategory', 'id', 'id', 'product_subcategory_id', 'product_category_id');
    }

    public function subcategory_with_category()
    {
        return $this->belongsTo('App\Models\ProductSubcategory', 'product_subcategory_id')->with('category');
    }

    public function similarProduct()
    {
        return $this->hasOne('App\Models\Product', 'product_subcategory_id', 'product_subcategory_id')->where('products.id', '!=', $this->id)
            ->whereBetween('price', [$this->price - $this->price * 50 / 100, $this->price + $this->price * 50 / 100])->withCount('solds')->orderBy('solds_count', 'DESC')->with('image');
    }

    public function similarProducts()
    {
        return $this->hasMany('App\Models\Product', 'product_subcategory_id', 'product_subcategory_id')->where('id', '!=', $this->id)->with('image')->take(10);
    }

    public function popularBrands()
    {
        return $this->hasMany('App\Models\Product', 'product_subcategory_id', 'product_subcategory_id')->where('products.id', '!=', $this->id)->withCount('solds')->take(10)->orderBy('solds_count', 'DESC')->with('image');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'product_brand_id')->withTrashed();
    }

    public function solds()
    {
        return $this->hasMany('App\Models\SoldProduct');
    }

    public function attributes()
    {
        return $this->hasMany(ProductSpecificationAttributeValue::class, 'product_id');
    }

    public function specification_attributes()
    {
        return $this->hasMany(ProductSpecificationAttributeValue::class, 'product_id')->with('attribute');
    }

    public function receiveds()
    {
        return $this->hasMany('App\Models\ReceivedProduct');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\ProductImage');
    }
    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review')->orderBy('created_at', 'desc')->take(5);
    }

    public function getRatingAttribute()
    {
        return $this->hasMany('App\Models\Review')->avg('rating');
    }

    public function getRatingCountAttribute()
    {
        return $this->hasMany('App\Models\Review')->count();
    }
}
