<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleMaterialWarehouse extends Model
{
    protected $fillable = [
        'sale_id',
        'material_id',
        'warehouse_material_id',
        'quantity',
        'back'
    ];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function warehouse_material(){
        return $this->belongsTo(WarehouseMaterial::class);
    }
}
