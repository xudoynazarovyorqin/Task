<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReadyProductItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['sale_id', 'product_id', 'selling_price', 'quantity', 'shipped','currency_id','rate'];

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function sale(){
        return $this->belongsTo(SaleReadyProduct::class, 'sale_id')->withTrashed();
    }

    public function output_products(){
        return $this->morphMany(OutputProduct::class, 'output_productable');
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}