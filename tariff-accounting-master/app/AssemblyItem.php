<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class AssemblyItem extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    use SoftDeletes;

    protected $fillable = ['assembly_id','product_id','quantity','ready','order_product_id'];

    public function assembly(){
        return $this->belongsTo(Assembly::class)->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function materials(){
        return $this->hasMany(AssemblyItemMaterial::class);
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
