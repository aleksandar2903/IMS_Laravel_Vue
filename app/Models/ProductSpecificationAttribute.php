<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductSpecificationAttribute extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function products()
    {
        return $this->hasManyThrough(ProductSpecificationAttributeValue::class, Product::class, 'id', 'attribute_id');
    }
}
