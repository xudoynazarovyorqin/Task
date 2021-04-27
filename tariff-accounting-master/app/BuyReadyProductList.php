<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\BuyReadyProduct;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyReadyProductList extends Model
{
    use SoftDeletes;

    protected $fillable = ['buy_id', 'product_id', 'qty_weight', 'buy_price', 'selling_price', 'not_enough', 'currency_id', 'rate'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function buy(){
        return $this->belongsTo(BuyReadyProduct::class, 'buy_id');
    }

    public function warehouse_products(){
        return $this->morphMany(WarehouseProduct::class, 'warehouse_productable');
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
