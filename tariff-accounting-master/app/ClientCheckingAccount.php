<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Client;

class ClientCheckingAccount extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	
    protected $fillable = [
      	'client_id', 'bank', 'address', 'correspondent_account', 'checking_account'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
