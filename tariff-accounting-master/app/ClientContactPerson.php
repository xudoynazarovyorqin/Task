<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Client;

class ClientContactPerson extends Model implements Auditable
{    
	use \OwenIt\Auditing\Auditable;
	
    protected $fillable = [
      	'client_id', 'full_name', 'position', 'phone', 'email', 'comment' 
    ];

    protected $table = 'client_contact_persons';

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
