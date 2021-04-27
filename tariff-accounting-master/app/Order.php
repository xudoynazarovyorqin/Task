<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Order extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;

    use SoftDeletes, ScopeTrait;

    const PRODUCTION = 'production';

    const ASSEMBLY = 'assembly';

    const TABLE_NAME = 'orders';

    protected $fillable = ['number','datetime','client_id','contract_client_id','state_id','priority_id','amount','paid','begin_date','end_date','parent_id','is_child','production_type'];

    protected $search_columns = ['id', 'number','datetime','amount', 'begin_date', 'end_date', 'paid'];

    public function products(){
        return $this->hasMany(OrderProduct::class);
    }

    public function costs(){
        return $this->hasMany(OrderCost::class);
    }

    public function client(){
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function contract_client(){
        return $this->belongsTo(ContractClient::class)->withTrashed();
    }

    public function state(){
        return $this->belongsTo(State::class)->withTrashed();
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class)->withTrashed();
    }

    public function scopeSearch($query, $string){
        $columns = $this->search_columns;
        $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'like',  '%' . $string .'%');
            }
            $query->orWhereIn('client_id',Client::search($string)->pluck('id')->toArray());
            $query->orWhereIn('state_id',State::search($string)->pluck('id')->toArray());
            $query->orWhereIn('priority_id',Priority::search($string)->pluck('id')->toArray());
        });
        return $query;
    }

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('number')){
            $query = $query->where('number',$filter);
        }
        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }
        if (request()->get('paid',null) != null){
            $filter = request()->get('paid');
            if ($filter == 0){
                $query = $query->where(function ($query) {
                    $query->orWhereRaw('(amount - paid) > 0')
                        ->orWhere([['paid','=',null],['amount', '>', 0]]);
                    return $query;
                });
            }else{
                $query = $query->whereRaw('(amount - paid) <= 0');
            }
        }

        if ($filter = request('client_id')){
            $query = $query->where('client_id',$filter);
        }

        if ($filter = request('owner')){
            $query = $query->where('owner','like','%' . $filter . '%');
        }

        if ($filter = request('amount')){
            $query = $query->where('amount','like','%' . $filter . '%');
        }

        if ($filter = request('payed_sum')){
            $query = $query->where('payed_sum','like','%' . $filter . '%');
        }

        if ($filter = request('state_id')){
            $query = $query->where('state_id',$filter);
        }

        if ($filter = request('priority_id')){
            $query = $query->where('priority_id',$filter);
        }

        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('begin_date')){
            $query = $query->whereDate('begin_date','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('end_date')){
            $query = $query->whereDate('end_date','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('contract_client_id')){
            $query = $query->where('contract_client_id',$filter);
        }

        if ($filter = request('production_type')){
            $query = $query->where('production_type','like','%' . $filter . '%');
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

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class,'auditable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function assemblies(): MorphMany
    {
        return $this->morphMany(Assembly::class,'assemblyable');
    }

    public function sales(): MorphMany
    {
        return $this->morphMany(Sale::class,'saleable');
    }

    public function scopePaid($query,$paid){
        if ($paid){
            $query = $query->whereRaw('(amount - paid) <= 0');
        }else{
            $query = $query->where(function ($query) {
                $query->orWhereRaw('(amount - paid) > 0')
                    ->orWhere([['paid','=',null],['amount', '>', 0]]);
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
