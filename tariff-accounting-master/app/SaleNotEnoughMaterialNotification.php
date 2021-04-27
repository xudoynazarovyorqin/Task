<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SaleNotEnoughMaterialNotification extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	
    const CREATED = 'created';

    const WAITING = 'waiting';

    const COMPLETED = 'completed';

    const CANCELED = 'canceled';

    protected $fillable = ['sale_id','status','body'];

    public function materials(){
        return $this->hasMany(SaleNotEnoughMaterial::class);
    }
}
