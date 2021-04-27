<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleAdditionalMaterial extends Model
{
    protected $fillable = ['material_id','quantity','sale_id','measurement_rate'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
