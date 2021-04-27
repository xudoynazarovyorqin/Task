<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseType extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'description'
    ];

    private $search_columns = [
        'id', 'name', 'description'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%' .  $filter . '%');
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

    public function warehouses(){
        return $this->hasMany(Warehouse::class, 'warehouse_type_id');
    }

}
