<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class ContractProviderProduct extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    protected $fillable = ['provider_contract_id', 'product_id', 'qty'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
