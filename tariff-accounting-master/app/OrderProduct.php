<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class OrderProduct extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    use SoftDeletes;

    protected $fillable = ['order_id','product_id','currency_id','rate','price','quantity','ready'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
