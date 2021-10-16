<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductCategory extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    protected $table = 'product_categories';
    protected $fillable = ['name'];
    public function products() {
        return $this->hasMany('App\Models\Product');
    }
}
