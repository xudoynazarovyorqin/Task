<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class WarehouseProduct extends Model implements Auditable
{
	use ScopeTrait;
	use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const PROVIDER = 'provider';
    const CLIENT = 'client';
    const FIRM = 'firm';

    const ABLE_TYPE = 'warehouse_productable_type';
    const ABLE_ID = 'warehouse_productable_id';

    const WAREHOUSE_ABLE_TYPE_ASSEMBLY_ITEM = 'assembly_items';

    const WAREHOUSE_ABLE_TYPE_SALE_PRODUCTS = 'sale_products';

    const WAREHOUSE_ABLE_TYPE_BUY_READY_PRODUCT_LISTS = 'buy_ready_product_lists';

    const AGENT_ABLE_TYPE_PROVIDERS = 'providers';

    const AGENT_ABLE_TYPE_CLIENTS = 'clients';

    const TABLE_NAME = 'warehouse_products';
    protected $table = 'warehouse_products';

    protected $fillable = [self::ABLE_TYPE, self::ABLE_ID, 'product_id', 'qty_weight', 'buy_price', 'remainder', 'warehouse_id',
        'selling_price', 'receive', 'owner', 'agentable_id', 'agentable_type', 'currency_id', 'rate','booked'];

    private $search_columns = ['id', self::ABLE_TYPE, self::ABLE_ID, 'product_id', 'qty_weight', 'buy_price', 'remainder', 'warehouse_id', 'selling_price', 'receive', 'owner', 'agentable_id', 'agentable_type'];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('agentable_id')){
            $query = $query->where('agentable_id', $filter);
        }
        if ($filter = request('product_id')){
            $query = $query->where('product_id', $filter);
        }
        if ($filter = request('warehouse_productable_type')){
            $query = $query->where(self::ABLE_TYPE, $filter);
        }
        if ($filter = request('warehouse_id')){
            $query = $query->where('warehouse_id', $filter);
        }
        if ($filter = request('receive')){
            $query = $query->where('receive','like','%' . $filter . '%');
        }
        if ($filter = request('remainder')){
            $query = $query->where('remainder','like','%' . $filter . '%');
        }
        if ($filter = request('selling_price')){
            $query = $query->where('selling_price','like','%' . $filter . '%');
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

    public function scopeType($query,$type){
        return $query->where(self::ABLE_TYPE,$type);
    }

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class)->withTrashed();
    }

    public function warehouse_productable()
    {
        return $this->morphTo();
    }

    public function agentable()
    {
        return $this->morphTo();
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
