<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealizationMaterial extends Model
{
    use SoftDeletes;

    const TABLE_NAME = 'realization_materials';

    protected $fillable = ['realization_id','material_id','quantity','issued_from_booked'];

    public function realization(){
        return $this->belongsTo(Realization::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
