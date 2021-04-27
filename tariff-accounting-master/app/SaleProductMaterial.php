<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleProductMaterial extends Model
{
    protected $fillable = ['sale_product_id','material_id','quantity','measurement_rate'];

    public function sale_product(){
        return $this->belongsTo(SaleProduct::class)->withTrashed();
    }

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }
}
