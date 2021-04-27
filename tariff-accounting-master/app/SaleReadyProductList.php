<?php

namespace App;

use App\Http\Controllers\Api\BuyController;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class SaleReadyProductList extends Model
{
    use SoftDeletes, ScopeTrait;

    const ON_CREATED = 'created';
    const ON_UPDATED = 'updated';

    protected $fillable = ['sale_id', 'product_id', 'purchase_price', 'selling_price', 'quantity', 'total_price', 'when_added', 'shipped', 'warehouse_product_id'];

	protected $search_columns = ['sale_id', 'product_id', 'purchase_price', 'selling_price', 'quantity', 'total_price', 'when_added', 'shipped', 'warehouse_product_id'];

    public function product(){
	    return $this->belongsTo(Product::class)->withTrashed();
    }

    public function sale(){
        return $this->belongsTo(SaleReadyProduct::class, 'sale_id')->withTrashed();
    }

    public function warehouse_product(){
        return $this->belongsTo(WarehouseProduct::class)->withTrashed();
    }
}
