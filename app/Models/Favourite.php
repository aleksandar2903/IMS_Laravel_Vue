<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->with('image');
    }
}
