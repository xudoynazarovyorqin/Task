<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssemblyMaterial extends Model
{
    protected $fillable = ['assembly_id', 'material_id', 'total','ready','waiting_to_buy'];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function assembly(){
        return $this->belongsTo(Assembly::class)->withTrashed();
    }
}
