<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class AssemblyAdditionalMaterial extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    use SoftDeletes;

    protected $fillable = ['material_id','quantity','assembly_id','measurement_rate'];

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function assembly(){
        return $this->belongsTo(Assembly::class)->withTrashed();
    }
}
