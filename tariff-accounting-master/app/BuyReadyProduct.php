<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class BuyReadyProduct extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'buy_ready_products';

    const OBJECT_TYPE_SALE = 'sales';
    const OBJECT_TYPE_ASSEMBLY = 'assemblies';

    protected $fillable = [
      'number','datetime','provider_id', 'paid', 'date', 'total_price', 'comment', 'status_id', 'object_id', 'object_type', 'is_warehouse', 'contract_provider_id', 'user_id', 'paid_price', 'buy_notification_id'
    ];

    private $search_columns = [
        'id', 'paid', 'date', 'total_price', 'comment', 'paid_price','number','datetime',
    ];

    public function scopeSearch($query, $string){
        if ($string)
        {
            $columns = $this->search_columns;
            $query->where(function ($query) use($string, $columns) {
                foreach ($columns as $column){
                    $query->orwhere($column, 'like',  '%' . $string .'%');
                }
                $query->orWhereIn('provider_id',Provider::search($string)->pluck('id')->toArray());
                $query->orWhereIn('status_id',State::search($string)->pluck('id')->toArray());
            });
        }
        return $query;
    }

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('number')){
            $query = $query->where('number',$filter);
        }
        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('provider_id')){
            $query = $query->where('provider_id', $filter);
        }
        if (request()->get('paid',null) != null){
            $filter = request()->get('paid');
            if ($filter == 0){
                $query = $query->where(function ($query) {
                    $query->orWhereRaw('(total_price - paid_price) > 0')
                        ->orWhere([['paid_price','=',null],['total_price', '>', 0]]);
                    return $query;
                });
            }else{
                $query = $query->whereRaw('(total_price - paid_price) <= 0');
            }
        }
        if ($filter = request('date')){
            $query = $query->whereDate('date','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('total_price')){
            $query = $query->where('total_price', 'like','%'. $filter . '%');
        }
        if ($filter = request('paid_price')){
            $query = $query->where('paid_price', 'like','%'. $filter . '%');
        }
        if ($filter = request('comment')){
            $query = $query->where('comment', 'like','%'. $filter . '%');
        }
        if ($filter = request('status_id')){
            $query = $query->where('status_id', $filter);
        }
        if ($filter = request('contract_provider_id')){
            $query = $query->where('contract_provider_id', $filter);
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
        }
        if ($filter = request('object_id')){
            $query = $query->where('object_id', $filter);
        }
        if ($filter = request('object_type')){
            $query = $query->where('object_type', 'like','%'. $filter . '%');
        }
        if ( request('is_warehouse') !== null ){
            $filter = request('is_warehouse');
            $query = $query->where('is_warehouse', $filter);
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
        return $this->belongsToMany(Product::class,'buy_ready_product_lists','buy_id','product_id')->withTrashed();
    }

    public function buyProducts(){
        return $this->hasMany(BuyReadyProductList::class, 'buy_id');
    }

    public function provider() {
        return $this->belongsTo(Provider::class);
    }

    public function contract_provider(){
        return $this->belongsTo(ContractProvider::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->morphMany('App\Payment', 'paymentable');
    }

    public function status(){
        return $this->belongsTo(State::class);
    }

    public function scopePaid($query,$paid){
        if ($paid){
            $query = $query->whereRaw('(total_price - paid_price) <= 0');
        }else{
            $query = $query->where(function ($query) {
                $query->orWhereRaw('(total_price - paid_price) > 0')
                    ->orWhere([['paid_price','=',null],['total_price', '>', 0]]);
                return $query;
            });
        }
        return $query;
    }

    public function scopeOldest($query, $column = null)
    {
        if (!$column) {
            $column = self::CREATED_AT;
        }

        return $query->orderBy($column, 'asc');
    }
}

