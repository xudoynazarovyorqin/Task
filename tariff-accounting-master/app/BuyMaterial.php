<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BuyMaterial extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['buy_id','material_id', 'qty_weight', 'price', 'selling_price','not_enough', 'currency_id', 'rate'];


    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function buy(){
        return $this->belongsTo(Buy::class)->withTrashed();
    }

    public function warehouse_materials(){
        return $this->morphMany(WarehouseMaterial::class, 'warehouse_materialable');
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
