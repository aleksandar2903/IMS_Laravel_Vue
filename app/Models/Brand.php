<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Brand extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    protected $table = 'brands';
    protected $fillable = ['name', 'image'];

    public function products() {
        return $this->hasMany('App\Models\Product', 'product_brand_id');
    }
}
