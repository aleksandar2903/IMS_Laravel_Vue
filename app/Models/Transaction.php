<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Transaction extends Model
{
    protected $fillable = [
        'title', 'reference', 'amount', 'payment_method_id', 'type', 'client_id', 'user_id', 'sale_id', 'provider_id', 'receipt_id'
    ];

    public function method()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Models\Receipt');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function scopeFindByPaymentMethodId($query, $id)
    {
        return $query->where('payment_method_id', $id);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month);
    }
}
