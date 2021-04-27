<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use ScopeTrait, SoftDeletes;

    const STATUS_WAITING = 'waiting';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENSE = 'suspense';
    const STATUS_CLOSED = 'closed';

    protected $fillable = ['state', 'status'];
    protected $search_columns = ['state', 'status'];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('state')){
            $query = $query->where('state','like','%' . $filter . '%');
        }
        if ($filter = request('status')){
            $query = $query->where('status','like','%' . $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function saleReadyProducts(){
        return $this->hasMany(SaleReadyProduct::class, 'state_id');
    }
}
