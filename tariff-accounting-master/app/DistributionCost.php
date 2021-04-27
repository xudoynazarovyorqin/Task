<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class DistributionCost extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes,Auditable,ScopeTrait;

    const TABLE_NAME = 'distribution_costs';

    protected $fillable = ['datetime', 'type', 'from_date', 'to_date', 'user_id'];

    protected $search_columns = ['datetime', 'from_date', 'to_date'];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }

        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }

        if($filter = request('type')) {
            $query = $query->where('type', '=', $filter);
        }

        if ($filter = request('from_date_attr')){
            $query = $query->whereDate('from_date','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('to_date_attr')){
            $query = $query->whereDate('to_date','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('user_id')){
            $query = $query->where('user_id',$filter);
        }

        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }

        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('datetime','>=',Carbon::parse($from)->toDateString());
        }

        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('datetime','<=',Carbon::parse($to)->toDateString());
        }

        return $query;
    }

    public function additional_prices(){
        return $this->hasMany(AdditionalPrice::class);
    }

    public function distribution_transactions(){
        return $this->hasMany(DistributionTransaction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
