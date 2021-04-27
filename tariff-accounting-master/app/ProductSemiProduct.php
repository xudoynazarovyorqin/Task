<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSemiProduct extends Model
{
    protected $fillable = ['product_id', 'semi_product_id','quantity'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function semi_product(){
        return $this->belongsTo(Product::class, 'semi_product_id')->withTrashed();
    }
}
