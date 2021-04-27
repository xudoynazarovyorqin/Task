<?php

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ContractClient extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const TABLE_NAME = 'contract_clients';

    protected $fillable = [
      	'number', 'begin_date', 'client_id', 'status_id', 'sum', 'remainder', 'comment', 'parent_id', 'paid', 'conclusion_date', 'termination_date'
    ];

    private $search_columns = [
        'id', 'number', 'begin_date', 'sum', 'remainder', 'comment', 'parent_id', 'paid', 'conclusion_date', 'termination_date'
    ];

    protected $casts = [
        'sum' => 'float',
        'remainder' => 'float',
        'paid' => 'float',
        'begin_date' => Controller::ELEMENT_DATE_FORMAT,
        'conclusion_date' => Controller::ELEMENT_DATE_FORMAT,
        'termination_date' => Controller::ELEMENT_DATE_FORMAT,
        'created_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
        'updated_at'  => Controller::ELEMENT_DATE_TIME_FORMAT,
    ];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('client_id',Client::search($string)->pluck('id')->toArray());
            $query->orWhereIn('status_id',State::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeSearchByClientId($query, $string){
        return $query->where('client_id', $string);
    }

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('number')){
            $query = $query->where('number','like','%'. $filter . '%');
        }
        if ($filter = request('begin_date')){
            $query = $query->whereDate('begin_date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('conclusion_date')){
            $query = $query->whereDate('conclusion_date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('termination_date')){
            $query = $query->whereDate('termination_date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('client_id')){
            $query = $query->where('client_id', $filter);
        }
        if ($filter = request('status_id')){
            $query = $query->where('status_id', $filter);
        }
        if ($filter = request('sum')){
            $query = $query->where('sum','like','%'. $filter . '%');
        }
        if ($filter = request('remainder')){
            $query = $query->where('remainder','like','%'. $filter . '%');
        }
        if ($filter = request('paid')){
            $query = $query->where('paid','like','%'. $filter . '%');
        }
        if ($filter = request('comment')){
            $query = $query->where('comment', 'like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('parent_id')){
            $query = $query->where('parent_id', $filter);
        }
        return $query;
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function application(){
        return $this->hasOne(Application::class,'contract_client_id','id');
    }

    public function parent(){
        return $this->belongsTo(ContractClient::class);
    }

    public function products(){
       return $this->hasMany(ContractClientProducts::class, 'contract_client_id');
    }

    public function suspenses(){
        return $this->hasMany(ContractClientSuspense::class, 'contract_client_id');
    }

    public function status(){
        return $this->belongsTo(State::class,'status_id');
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }
}
