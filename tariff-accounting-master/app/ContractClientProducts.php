<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ContractClientProducts extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    protected $fillable = ['contract_client_id', 'product_id', 'qty'];

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function contract_client(){
        return $this->belongsTo(ContractClient::class);
    }
}
