<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const DEFAULT_CURRENCY_ID = 1;
    const DEFAULT_CURRENCY_RATE = 1;
    const MULTI_CURRENCY = true;

    protected $fillable = ['name', 'rate', 'symbol','reverse','reversed_rate','code'];

    private $search_columns = [
        'id', 'name', 'rate', 'symbol'
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','ilike','%' . $filter . '%');
        }
        if ($filter = request('rate')){
            $query = $query->where('rate','like','%' . $filter . '%');
        }
        if ($filter = request('symbol')){
            $query = $query->where('symbol','like','%' . $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }

        return $query;
    }
}
