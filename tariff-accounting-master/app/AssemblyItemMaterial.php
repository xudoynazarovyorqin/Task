<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class AssemblyItemMaterial extends Model
{

    protected $fillable = ['assembly_item_id','material_id','quantity','ready','measurement_rate'];

    public function assembly_item(){
        return $this->belongsTo(AssemblyItem::class)->withTrashed();
    }

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }
}
