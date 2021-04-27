<?php

namespace App;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Cost extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const TABLE_NAME = 'costs';

    protected $fillable = [
        'name', 'description', 'sku', 'amount', 'currency_id', 'is_distribution'
    ];

    protected $casts = [
        'amount'            => 'float',
        'is_distribution'   => 'boolean',
        'created_at'        => 'datetime:' . Controller::ELEMENT_DATE_TIME_FORMAT,
        'updated_at'        => 'datetime:' . Controller::ELEMENT_DATE_TIME_FORMAT,
    ];

    private $search_columns = [
        'id', 'name', 'description', 'sku', 'amount'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('amount')){
            $query = $query->where('amount','like','%' .  $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%'. $filter . '%');
        }
        if ($filter = request('sku')){
            $query = $query->where('sku','like','%'. $filter . '%');
        }
        if (request()->get('is_distribution',null) != null){
            $filter = request()->get('is_distribution');
            $query = $query->where('is_distribution', '=', $filter);
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('created_at','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('created_at','<=',Carbon::parse($to)->toDateString());
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }

        return $query;
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
