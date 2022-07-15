<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductImage extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belingsTo('App\Models\Product');
    }
}
