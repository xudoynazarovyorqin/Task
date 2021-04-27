<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseMaterial extends Model implements Auditable
{
	use ScopeTrait;
	use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const ABLE_TYPE_BUY_MATERIAL = 'buy_materials';
    const ABLE_TYPE = 'warehouse_materialable_type';
    const ABLE_ID = 'warehouse_materialable_id';
    const TABLE_NAME = 'warehouse_materials';

    protected $table = 'warehouse_materials';

    protected $fillable = ['buy_id',  'warehouse_materialable_id','warehouse_materialable_type', 'material_id',
        'price', 'remainder', 'warehouse_id', 'total_coming', 'booked', 'is_reworked', 'currency_id', 'rate', 'buy_price'];

    private $search_columns = ['id', 'price', 'remainder', 'total_coming', 'is_reworked'];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('material_id')){
            $query = $query->where('material_id', $filter);
        }
        if ($filter = request('price')){
            $query = $query->where('price', 'like','%'. $filter . '%');
        }
        if ($filter = request('remainder')){
            $query = $query->where('remainder', 'like','%'. $filter . '%');
        }
        if ($filter = request('total_coming')){
            $query = $query->where('total_coming', 'like','%'. $filter . '%');
        }
        if ($filter = request('warehouse_id')){
            $query = $query->where('warehouse_id', $filter);
        }
        if ($filter = request('created_at')){
            $query = $query->where('created_at', 'like','%'. $filter . '%');
        }
        if ($filter = request('updated_at')){
            $query = $query->where('updated_at', 'like','%'. $filter . '%');
        }

        return $query;
    }

    public function scopeType($query,$type){
        return $query->where(self::ABLE_TYPE,$type);
    }

    public function warehouse_materialable()
    {
        return $this->morphTo();
    }

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class)->withTrashed();
    }

    public function currency(){
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    public function additional_prices() {
        return $this->morphMany(AdditionalPrice::class, 'additional_priceable');
    }

    public function scopeSortSetting($query){
        return $query->orderBy('created_at','asc');
    }
}
