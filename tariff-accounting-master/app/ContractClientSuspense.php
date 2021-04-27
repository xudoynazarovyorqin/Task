<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ContractClientSuspense extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = ['contract_client_id', 'from_date', 'to_date', 'comment'];

    public function contract_client(){
        return $this->belongsTo(ContractClient::class);
    }
}
