<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    protected $fillable = ['product_id', 'material_id','quantity','inverse_quantity'];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
