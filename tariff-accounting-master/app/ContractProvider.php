<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ContractProvider extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'contract_providers';

    protected $fillable = [
      	'number', 'begin_date', 'provider_id', 'status_id', 'sum', 'comment', 'parent_id', 'paid'
    ];

    private $search_columns = [
        'id', 'number', 'begin_date', 'sum', 'comment', 'parent_id', 'paid'
    ];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('provider_id',Client::search($string)->pluck('id')->toArray());
            $query->orWhereIn('status_id',State::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('number')){
            $query = $query->where('number','like','%'. $filter . '%');
        }
        if ($filter = request('provider_id')){
            $query = $query->where('provider_id', $filter);
        }
        if ($filter = request('parent_id')){
            $query = $query->where('parent_id', $filter);
        }
        if ($filter = request('status_id')){
            $query = $query->where('status_id', $filter);
        }
        if ($filter = request('sum')){
            $query = $query->where('sum','like','%'. $filter . '%');
        }
        if ($filter = request('paid')){
            $query = $query->where('paid','like','%'. $filter . '%');
        }
        if ($filter = request('comment')){
            $query = $query->where('comment', 'like', '%' . $filter . '%');
        }
        if ($filter = request('begin_date')){
            $query = $query->whereDate('begin_date','=',Carbon::parse($filter)->toDateString());
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

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function parent(){
        return $this->belongsTo(ContractProvider::class);
    }

    public function materials(){
       return $this->hasMany(ContractProviderMaterials::class, 'provider_contract_id');
    }

    public function products(){
       return $this->hasMany(ContractProviderProduct::class, 'provider_contract_id');
    }

    public function status(){
        return $this->belongsTo(State::class,'status_id');
    }

     public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }
}
