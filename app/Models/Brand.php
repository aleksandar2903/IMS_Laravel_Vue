<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Faker\Core\Number;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Decimal;

class Brand extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    protected $table = 'brands';
    protected $fillable = ['name', 'image'];

    // protected $appends = [
    //     'max_product_price',
    //     'min_product_price',
    // ];

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_brand_id');
    }

    public function getMaxProductPriceAttribute()
    {
        return $this->products()->max('price');
    }

    public function getMinProductPriceAttribute()
    {
        return $this->products()->min('price');
    }
}
