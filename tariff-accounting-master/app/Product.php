<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Bek96\Album\Traits\HasAlbum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Carbon\Carbon;

class Product extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use ScopeTrait, HasAlbum, SoftDeletes,Auditable;

    const PRODUCTION = 'production';
    const ASSEMBLY = 'assembly';

    const TABLE_NAME = 'products';

    protected $fillable = [
        'name', 'code', 'weight', 'nds', 'minimum_price', 'purchase_price', 'selling_price',
        'minimum_balance', 'description', 'measurement_id', 'country_id', 'vendor_code',
        'recycled', 'warehouse_type_id', 'production', 'production_type',
        'purchase_currency_id','selling_currency_id'
    ];

    private $search_columns = [
        'id','name', 'code', 'weight', 'nds', 'minimum_price', 'purchase_price', 'selling_price', 'description', 'measurement_id', 'country_id', 'vendor_code','recycled', 'warehouse_type_id', 'production', 'production_type'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','ilike','%' .  $filter . '%');
        }
        if ($filter = request('code')){
            $query = $query->where('code','ilike','%'. $filter . '%');
        }
        if ($filter = request('measurement_id')){
            $query = $query->where('measurement_id', $filter);
        }
        if ($filter = request('selling_price')){
            $query = $query->where('selling_price', 'like','%'. $filter . '%');
        }
        if ($filter = request('weight')){
            $query = $query->where('weight', 'like','%'. $filter . '%');
        }
        if ($filter = request('country_id')){
            $query = $query->where('country_id', 'like','%'. $filter . '%');
        }
        if ($filter = request('purchase_price')){
            $query = $query->where('purchase_price', 'like','%'. $filter . '%');
        }
        if ($filter = request('minimum_price')){
            $query = $query->where('minimum_price', 'like','%'. $filter . '%');
        }
        if ($filter = request('vendor_code')){
            $query = $query->where('vendor_code', 'like','%'. $filter . '%');
        }
        if ($filter = request('recycled')){
            $query = $query->where('recycled', 'like','%'. $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description', 'like','%'. $filter . '%');
        }
        if ($filter = request('minimum_balance')){
            $query = $query->where('minimum_balance', 'like','%'. $filter . '%');
        }
        if ($filter = request('nds')){
            $query = $query->where('nds', 'like','%'. $filter . '%');
        }
        if ($filter = request('warehouse_type_id')){
            $query = $query->where('warehouse_type_id', $filter);
        }
        if ( request('production') !== null ){
            $filter = request('production');
            $query = $query->where('production', $filter);
        }
        if ($filter = request('production_type')){
            $query = $query->where('production_type', $filter);
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

    public function materials(){
        return $this->hasMany(ProductMaterial::class);
    }

    public function measurement(){
        return $this->belongsTo(Measurement::class)->withTrashed();
    }

    public function country(){
        return $this->belongsTo(Country::class)->withTrashed();
    }

    public function categories(){
        return $this->belongsToMany(Category::class,'category_products')->withTrashed();
    }

    public function warehouse_type(){
        return $this->belongsTo(WarehouseType::class, 'warehouse_type_id')->withTrashed();
    }

    public function warehouse_products(){
        return $this->hasMany(WarehouseProduct::class);
    }

    public function semi_products(){
        return $this->hasMany(ProductSemiProduct::class, 'product_id', 'id');
    }

    public function products(){
        return $this->hasMany(ProductSemiProduct::class, 'semi_product_id', 'id');
    }

    public function selling_currency(){
        return $this->belongsTo(Currency::class,'selling_currency_id')->withTrashed();
    }

    public function purchase_currency(){
        return $this->belongsTo(Currency::class,'purchase_currency_id')->withTrashed();
    }
}
