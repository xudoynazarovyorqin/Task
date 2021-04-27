<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class Client extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const TABLE_NAME = 'clients';

    protected $fillable = [
      	'name', 'full_name', 'sku', 'phone', 'fax', 'email', 'comment', 'actual_address', 'type_id',
        'legal_address', 'inn', 'mfo', 'okonx', 'oked', 'rkp_nds', 'balance',
        'object_name', 'district_id', 'quarter_id', 'object_street', 'object_home', 'object_corps', 'object_flat',
    ];

    private $search_columns = [
        'id', 'name', 'full_name', 'sku', 'phone', 'fax', 'email', 'comment', 'actual_address', 'type_id',
        'legal_address', 'inn', 'mfo', 'okonx', 'oked', 'rkp_nds', 'balance',
        'object_name', 'object_street', 'object_home', 'object_corps', 'object_flat',
    ];

    public function scopeSearchById($query, $string) {
        return $query->where('id', $string);
    }

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','ilike','%'. $filter . '%');
        }
        if ($filter = request('phone')){
            $query = $query->where('phone','like','%'. $filter . '%');
        }
        if ($filter = request('email')){
            $query = $query->where('email','ilike','%'. $filter . '%');
        }
        if ($filter = request('actual_address')){
            $query = $query->where('actual_address','ilike','%'. $filter . '%');
        }
        if ($filter = request('type_id')){
            $query = $query->where('type_id', $filter);
        }
        if ($filter = request('balance')){
            $query = $query->where('balance','like','%'. $filter . '%');
        }
        if ($filter = request('object_name')){
            $query = $query->where('object_name','like','%' . $filter . '%');
        }
        if ($filter = request('district_id')){
            $query = $query->where('district_id',$filter);
        }
        if ($filter = request('quarter_id')){
            $query = $query->where('quarter_id',$filter);
        }
        if ($filter = request('object_street')){
            $query = $query->where('object_street','like','%' . $filter . '%');
        }
        if ($filter = request('object_home')){
            $query = $query->where('object_home','like','%' . $filter . '%');
        }
        if ($filter = request('object_corps')){
            $query = $query->where('object_corps','like','%' . $filter . '%');
        }
        if ($filter = request('object_flat')){
            $query = $query->where('object_flat','like','%' . $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function client_checking_accounts() {
        return $this->hasMany('App\ClientCheckingAccount');
    }

    public function client_contact_persons() {
        return $this->hasMany('App\ClientContactPerson');
    }

    public function contracts() {
        return $this->hasMany('App\ContractClient');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function sale_ready_products() {
        return $this->hasMany('App\SaleReadyProduct');
    }

    public function payments()
    {
        return $this->morphMany('App\Payment', 'agentable');
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function quarter(){
        return $this->belongsTo(Quarter::class);
    }

    public static function getTypes()
    {
        return array(
            array('id' => 1, 'name' => 'Юридическое лицо'),
            array('id' => 3, 'name' => 'Физическое лицо'),
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
