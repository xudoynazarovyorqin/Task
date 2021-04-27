<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotEnoughProduct extends Model
{
    protected $fillable = ['buy_ready_product_notification_id','product_id','quantity'];

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
