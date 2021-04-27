<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SaleReadyProduct extends Model implements Auditable
{
    use ScopeTrait, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'sale_ready_products';

    protected $fillable = ['number','datetime','client_id','total_price', 'paid_price', 'state_id', 'contract_client_id', 'user_id'];

    protected $search_columns = ['id', 'number','datetime','total_price', 'paid_price', 'contract_client_id', 'user_id'];

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('client_id',Client::search($string)->pluck('id')->toArray());
            $query->orWhereIn('state_id',State::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query)
	{
		if ($filter = request('number')){
		    $query = $query->where('number',$filter);
		}
        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('client_id')){
		    $query = $query->where('client_id',$filter);
		}
		if ($filter = request('user_id')){
		    $query = $query->where('user_id', $filter);
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
		if ($filter = request('total_price')){
		    $query = $query->where('total_price','like','%' . $filter . '%');
		}
		if ($filter = request('paid_price')){
		    $query = $query->where('paid_price','like','%' . $filter . '%');
		}
		if ($filter = request('state_id')){
		    $query = $query->where('state_id','like','%' . $filter . '%');
		}
		if ($filter = request('contract_client_id')){
           $query = $query->where('contract_client_id', $filter);
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

    public function items(){
       return $this->hasMany(SaleReadyProductItem::class, 'sale_id');
    }

    public function client(){
       return $this->belongsTo(Client::class);
    }

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function state(){
       return $this->belongsTo(State::class)->withTrashed();
    }

    public function contract_client(){
        return $this->belongsTo(ContractClient::class)->withTrashed();
    }

    public function payments()
    {
        return $this->morphMany('App\Payment', 'paymentable');
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
