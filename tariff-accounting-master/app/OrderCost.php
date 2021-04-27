<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class OrderCost extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    use SoftDeletes;

    protected $fillable = ['order_id','cost_id','currency_id','rate','amount'];

    public function cost(){
        return $this->belongsTo(Cost::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
