<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class OutputMaterial extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes;
    use Auditable;

    const OUTPUT_MATERIAL_TYPE_ASSEMBLIES = 'assemblies';

    const OUTPUT_MATERIAL_TYPE_SALE = 'sales';
    const ABLE_ID = 'output_materialable_id';
    const ABLE_TYPE = 'output_materialable_type';

    const SOURCEABLE_TYPE  = 'sourceable_type';
    const SOURCEABLE_ID  = 'sourceable_id';
    const TABLE_NAME = 'output_materials';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,self::SOURCEABLE_TYPE,self::SOURCEABLE_ID,'material_id','warehouse_material_id','quantity','back','issued_from_reservation'];

    public function output_materialable()
    {
        return $this->morphTo();
    }

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function warehouse_material(){
        return $this->belongsTo(WarehouseMaterial::class);
    }

    public function scopeAble($query,$table,$id){
        return $query->where(self::ABLE_TYPE,$table)
            ->where(self::ABLE_ID,$id);
    }
}
