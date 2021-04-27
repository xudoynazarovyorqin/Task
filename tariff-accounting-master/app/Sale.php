<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Sale extends Model implements Auditable
{
    use ScopeTrait,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'sales';

    const FOR_FIRM = 'firm';
    const FOR_CLIENT = 'client';

    const SALE_TYPE_ORDERS = 'orders';
    const SALE_TYPE_ASSEMBLY = 'assemblies';
    const SALE_TYPE_WAREHOUSE = 'warehouse';

    const ABLE_TYPE = 'saleable_type';
    const ABLE_ID = 'saleable_id';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,'number','datetime',
        'owner','parent_id','is_child', 'begin_date', 'end_date', 'priority_id',
        'state_id','level_id','reservation_of'];

    protected $search_columns = ['id', 'owner', 'begin_date', 'end_date','number','datetime'];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('state_id',State::search($string)->pluck('id')->toArray())
                ->orWhereIn('priority_id',Priority::search($string)->pluck('id')->toArray())
                ->orWhereIn('level_id',Level::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query)
   {
       if ($filter = request('id')){
            $query = $query->where('id',$filter);
       }

       if ($filter = request('datetime')){
           $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
       }

       if ($filter = request('state_id')){
            $query = $query->where('state_id',$filter);
       }

       if ($filter = request('owner')){
            $query = $query->where('owner','like','%' . $filter . '%');
       }

       if ($filter = request('priority_id')){
            $query = $query->where('priority_id',$filter);
       }

       if ($filter = request('saleable_type')){
           if ($filter == self::SALE_TYPE_WAREHOUSE)
               $query = $query->where('saleable_type',null);
           else
               $query = $query->where('saleable_type','like','%' . $filter . '%');
       }

       if ($filter = request('saleable_id')){
           $query = $query->where('saleable_id',$filter);
       }

       if ($filter = request('begin_date')){
           $query = $query->whereDate('begin_date','=',Carbon::parse($filter)->toDateString());
        }

       if ($filter = request('end_date')){
           $query = $query->whereDate('end_date','=',Carbon::parse($filter)->toDateString());
        }

       if ($filter = request('level_id')){
           $query = $query->where('level_id','like','%' . $filter . '%');
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

    public function products(){
       return $this->hasMany(SaleProduct::class);
    }

    public function sale_materials(){
        return $this->hasMany(SaleMaterial::class);
    }

    public function state(){
       return $this->belongsTo(State::class)->withTrashed();
    }

    public function priority(){
       return $this->belongsTo(Priority::class)->withTrashed();
    }

    public function comments(){
       return $this->morphMany(Comment::class, 'commentable');
    }

    public function level(){
       return $this->belongsTo(Level::class)->withTrashed();
    }

    public function not_enough_materials(){
       return $this->hasMany(SaleNotEnoughMaterial::class);
    }

    public function materials(){
       return $this->hasMany(SaleProductMaterial::class);
    }

    public function histories(){
        return $this->hasMany(SaleHistory::class,'sale_id','id');
    }

    public function created_info(){
        return $this->hasOne(SaleCreatedInfo::class,'sale_id','id');
    }
    public function contract_client(){
        return $this->belongsTo(ContractClient::class)->withTrashed();
    }

    public function payments()
    {
        return $this->morphMany('App\Payment', 'paymentable');
    }

    public function users(){
        return $this->belongsToMany(User::class,'sale_users')->withTrashed();
    }

    public function users_with_employee_group(){
        return $this->hasMany(SaleUser::class,'sale_id');
    }

    public function additional_materials(){
        return $this->hasMany(SaleAdditionalMaterial::class);
    }

    public function costs()
    {
        return $this->hasMany(SaleCost::class);
    }

    public function warehouse_products()
    {
        $sale_product_ids = $this->products->pluck('id');
        $old_warehouse_products = WarehouseProduct::with('product.measurement', 'warehouse')->where(WarehouseProduct::ABLE_TYPE, WarehouseProduct::WAREHOUSE_ABLE_TYPE_SALE_PRODUCTS)->whereIn(WarehouseProduct::ABLE_ID, $sale_product_ids)->get();
        return $old_warehouse_products;
    }

    public function percent()
    {
        $sale_product_ids = $this->products->pluck('id');
        $sale_products_sum = $this->products()->sum('quantity');
        $warehouse_products_sum = WarehouseProduct::where('warehouse_productable_type', 'sale_products')->whereIn('warehouse_productable_id', $sale_product_ids)->sum('receive');
        $defect_products_sum = DefectProduct::where('defectable_type', 'sale_products')->whereIn('defectable_id', $sale_product_ids)->sum('quantity');
        $percent = ($sale_products_sum) ? round((($warehouse_products_sum + $defect_products_sum) * 100) / $sale_products_sum) : 0;
        return $percent;
    }

    public function saleable()
    {
        return $this->morphTo();
    }

    public function defect_products(){
        return DefectProduct::where('defectable_type','sale_products')->whereIn('defectable_id',$this->products()->pluck('id')->toArray());
    }
}
