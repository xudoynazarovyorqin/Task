<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Assembly extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    use ScopeTrait;
    use SoftDeletes;

    const ABLE_ID = 'assemblyable_id';
    const ABLE_TYPE = 'assemblyable_type';

    const SALE_TYPE_ORDERS = 'orders';

    const ASSEMBLY_TYPE_ORDERS = 'orders';
    const TABLE_NAME = 'assemblies';

    protected $fillable = ['datetime','owner','parent_id','is_child', 'begin_date',
        'end_date', 'priority_id', 'state_id','level_id',
        self::ABLE_ID,self::ABLE_TYPE,'reservation_of'];

    protected $search_columns = ['id', 'owner', 'begin_date', 'end_date'];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('state_id',State::search($string)->pluck('id')->toArray())
                ->orWhereIn('priority_id',Priority::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function assemblyable()
    {
        return $this->morphTo();
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
            $query = $query->where('state_id','like','%' . $filter . '%');
        }

        if ($filter = request('owner')){
            $query = $query->where('owner','like','%' . $filter . '%');
        }

        if ($filter = request('priority_id')){
            $query = $query->where('priority_id','like','%' . $filter . '%');
        }

        if ($filter = request('assemblyable_type')){
            if ($filter == 'warehouse')
                $query = $query->where(self::ABLE_TYPE,null);
            else
                $query = $query->where(self::ABLE_TYPE,'like','%' . $filter . '%');
        }

        if ($filter = request('assemblyable_id')){
            $query = $query->where(self::ABLE_ID,'like','%' . $filter . '%');
        }

        if ($filter = request('begin_date')){
            $query = $query->whereDate('begin_date','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('end_date')){
            $query = $query->whereDate('end_date','=',Carbon::parse($filter)->toDateString());
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

    public function sales(){
        return $this->morphMany(Sale::class,'saleable');
    }

    public function items(){
        return $this->hasMany(AssemblyItem::class);
    }

    public function state(){
        return $this->belongsTo(State::class)->withTrashed();
    }

    public function priority(){
        return $this->belongsTo(Priority::class)->withTrashed();
    }

    public function users(){
        return $this->belongsToMany(User::class,'assembly_users');
    }

    public function users_with_employee_group(){
        return $this->hasMany(AssemblyUser::class,'assembly_id');
    }

    public function assembly_materials(){
        return $this->hasMany(AssemblyMaterial::class);
    }

    public function assembly_products(){
        return $this->hasMany(AssemblyProduct::class);
    }

    public function additional_materials(){
        return $this->hasMany(AssemblyAdditionalMaterial::class);
    }

    public function percent()
    {
        $assembly_item_ids = $this->items->pluck('id');
        $assembly_item_sum = $this->items()->sum('quantity');
        $warehouse_products_sum = WarehouseProduct::where('warehouse_productable_type', WarehouseProduct::WAREHOUSE_ABLE_TYPE_ASSEMBLY_ITEM)->whereIn('warehouse_productable_id', $assembly_item_ids)->sum('receive');
        $defect_products_sum = DefectProduct::where('defectable_type', 'assembly_items')->whereIn('defectable_id', $assembly_item_ids)->sum('quantity');

        $percent = ($assembly_item_sum) ? round((($warehouse_products_sum + $defect_products_sum) * 100) / $assembly_item_sum) : 0;

        return $percent;
    }

    public function warehouse_products()
    {
        return WarehouseProduct::where('warehouse_productable_type', WarehouseProduct::WAREHOUSE_ABLE_TYPE_ASSEMBLY_ITEM)->whereIn('warehouse_productable_id', $this->items()->pluck('id')->toArray());
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class,'auditable');
    }

    public function defect_products(){
        return DefectProduct::where('defectable_type','assembly_items')->whereIn('defectable_id',$this->items()->pluck('id')->toArray());
    }
}
