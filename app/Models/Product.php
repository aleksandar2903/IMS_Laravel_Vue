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

    public function category()
    {
        return $this->belongsTo('App\Models\ProductSubcategory', 'product_subcategory_id');
    }

    public function subcategory_with_category()
    {
        return $this->belongsTo('App\Models\ProductSubcategory', 'product_subcategory_id')->with('category');
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
}
