<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleMaterial extends Model
{
    protected $fillable = [
        'sale_id',
        'material_id',
        'ready',
        'total',
        'waiting_to_buy'
    ];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }
}
