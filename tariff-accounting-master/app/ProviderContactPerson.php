<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Provider;
use OwenIt\Auditing\Contracts\Auditable;

class ProviderContactPerson extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	
    protected $fillable = [
      	'provider_id', 'full_name', 'position', 'phone', 'email', 'comment' 
    ];

    protected $table = 'provider_contact_persons';

    public function provider_id(){
        return $this->belongsTo(Provider::class);
    }
}
