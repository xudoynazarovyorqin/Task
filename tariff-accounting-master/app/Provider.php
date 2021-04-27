<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\ProviderCheckingAccount;
use App\ProviderContactPerson;
use OwenIt\Auditing\Contracts\Auditable;

class Provider extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'providers';

    protected $fillable = [
      	'name', 'full_name', 'sku', 'phone', 'fax', 'email', 'comment', 'actual_address', 'type_id', 'legal_address', 'inn', 'mfo', 'okonx', 'oked', 'rkp_nds', 'balance'
    ];

    private $search_columns = [
        'id', 'name', 'full_name', 'sku', 'phone', 'fax', 'email', 'comment', 'actual_address', 'type_id', 'legal_address', 'inn', 'mfo', 'okonx', 'oked', 'rkp_nds', 'balance'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%'. $filter . '%');
        }
        if ($filter = request('phone')){
            $query = $query->where('phone','like','%'. $filter . '%');
        }
        if ($filter = request('email')){
            $query = $query->where('email','like','%'. $filter . '%');
        }
        if ($filter = request('actual_address')){
            $query = $query->where('actual_address','like','%'. $filter . '%');
        }
        if ($filter = request('type_id')){
            $query = $query->where('type_id', $filter);
        }
        if ($filter = request('balance')){
            $query = $query->where('balance','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function provider_checking_accounts() {
        return $this->hasMany('App\ProviderCheckingAccount');
    }

    public function buys(){
        return $this->hasMany(Buy::class);
    }

    public function buy_ready_products(){
        return $this->hasMany(BuyReadyProduct::class);
    }

    public function provider_contact_persons() {
        return $this->hasMany('App\ProviderContactPerson');
    }

    public function contracts() {
        return $this->hasMany('App\ContractProvider');
    }

    public function payments()
    {
        return $this->morphMany('App\Payment', 'agentable');
    }

    public static function getTypes()
    {
        return array(
            1 => array('id' => 1, 'name' => 'Юридическое лицо'),
            3 => array('id' => 3, 'name' => 'Физическое лицо'),
        );
    }

    public function type(){
        $types = self::getTypes();
        foreach($types as $struct) {
            if ($this->type_id == $struct['id']) {
                return $struct;
            }
        }
        return null;
    }
}
