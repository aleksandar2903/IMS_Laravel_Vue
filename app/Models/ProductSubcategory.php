<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductSubcategory extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $guarded = [];

    public function products() {
        return $this->hasMany('App\Models\Product', 'product_subcategory_id');
    }

    public function category() {
        return $this->belongsTo('App\Models\ProductCategory', 'product_category_id');
    }
}
