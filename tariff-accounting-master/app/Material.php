<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Material extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'materials';

    protected $fillable = [
        'name',
        'measurement_changeable','additional_measurement_id','additional_measurement_rate',
        'sku', 'is_active', 'price','price_currency_id',
        'type_id', 'measurement_id', 'warehouse_type_id', 'code', 'country_id', 'critical_weight', 'is_reworking'
    ];

    private $search_columns = [
        'id', 'name', 'sku', 'is_active', 'price', 'code'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','ilike','%' .  $filter . '%');
        }
        if ($filter = request('type_id')){
            $query = $query->where('type_id', $filter);
        }
        if ($filter = request('warehouse_type_id')){
            $query = $query->where('warehouse_type_id', $filter);
        }
        if ($filter = request('measurement_id')){
            $query = $query->where('measurement_id', $filter);
        }
        if ($filter = request('country_id')){
            $query = $query->where('country_id', $filter);
        }
        if ($filter = request('sku')){
            $query = $query->where('sku','like','%'. $filter . '%');
        }
        if ($filter = request('code')){
            $query = $query->where('code','like','%'. $filter . '%');
        }
        if ($filter = request('price')){
            $query = $query->where('price','like','%'. $filter . '%');
        }
        if ($filter = request('is_active')){
            $query = $query->where('is_active', $filter);
        }
        if ($filter = request('is_reworking') ){
            $query = $query->where('is_reworking', $filter);
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

    public function scopeReworking($query){
        $query = $query->where('is_reworking', 1);
        return $query;
    }

    public function measurement(){
        return $this->belongsTo(Measurement::class)->withTrashed();
    }

    public function additional_measurement(){
        return $this->belongsTo(Measurement::class)->withTrashed();
    }

    public function country(){
        return $this->belongsTo(Country::class)->withTrashed();
    }

    public function warehouse_type(){
        return $this->belongsTo(WarehouseType::class, 'warehouse_type_id')->withTrashed();
    }

    public function warehouse_materials(){
        return $this->hasMany(WarehouseMaterial::class);
    }

    public static function getTypes()
    {
        $result = array(
            1 => array('id' => 1, 'name' => 'Основное'),
            2 => array('id' => 2, 'name' => 'Полуготовое'),
            3 => array('id' => 3, 'name' => 'Другое'),
        );
        return $result;
    }

    public function price_currency(){
        return $this->belongsTo(Currency::class,'price_currency_id');
    }
}
