<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Country extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;

    protected $fillable = [
        'name', 'full_name', 'code'
    ];

    private $search_columns = [
        'id', 'name', 'full_name', 'code'
    ];


    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('full_name')){
            $query = $query->where('full_name','like','%' .  $filter . '%');
        }
        if ($filter = request('code')){
            $query = $query->where('code','like','%'. $filter . '%');
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
}
