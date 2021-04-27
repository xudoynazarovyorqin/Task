<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Warehouse extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'type', 'warehouse_type_id'];

    private $search_columns = [
        'id', 'name', 'type', 'warehouse_type_id'
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('type')){
            $query = $query->where('type','like','%' . $filter . '%');
        }
        if ($filter = request('warehouse_type_id')){
            $query = $query->where('warehouse_type_id', $filter);
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

    public static function types(){
        return [
            'product'     => 'Продукция',
            'material'   => 'Сырье'
        ];
    }

    public function products(){
        return $this->hasMany(WarehouseProduct::class);
    }

    public function warehouse_type(){
        return $this->belongsTo(WarehouseType::class);
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }
}
