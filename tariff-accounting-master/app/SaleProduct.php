<?php

namespace App;

use App\Http\Controllers\Api\BuyController;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class SaleProduct extends Model
{
    use SoftDeletes, ScopeTrait;

    const ON_CREATED = 'created';
    const ON_UPDATED = 'updated';


    protected $fillable = ['sale_id', 'product_id', 'price', 'quantity', 'total_amount','when_added','order_product_id', 'ready'];

	protected $search_columns = ['sale_id', 'product_id', 'price', 'quantity', 'total_amount', 'ready'];


    public function product(){
	    return $this->belongsTo(Product::class)->withTrashed();
    }

    public function sale(){
        return $this->belongsTo(Sale::class)->withTrashed();
    }

    public function materials(){
        return $this->hasMany(SaleProductMaterial::class);
    }

    public function not_enough_materials(){
        return $this->hasMany(SaleNotEnoughMaterial::class,'sale_product_id','id');
    }

    public function defect_products(){
        return $this->morphMany(DefectProduct::class, 'defectable');
    }

    public function warehouse_products(){
        return $this->morphMany(WarehouseProduct::class, 'warehouse_productable');
    }

    public function order_product(){
        return $this->belongsTo(OrderProduct::class)->withTrashed();
    }
}
