<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Provider;
use OwenIt\Auditing\Contracts\Auditable;

class ProviderCheckingAccount extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	
    protected $fillable = [
      	'provider_id', 'bank', 'address', 'correspondent_account', 'checking_account'
    ];

    public function provider(){
        return $this->belongsTo(Provider::class);
    }
}
