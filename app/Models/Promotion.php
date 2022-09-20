<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'image',
    ];

    public function products()
    {
        return $this->hasManyThrough('App\Models\Product', 'App\Models\FeaturedProduct', 'promotion_id', 'id', 'id', 'product_id')->with('image');
    }
}
