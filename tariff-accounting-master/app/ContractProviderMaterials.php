<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ContractProviderMaterials extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    protected $fillable = ['provider_contract_id', 'material_id', 'qty'];

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
