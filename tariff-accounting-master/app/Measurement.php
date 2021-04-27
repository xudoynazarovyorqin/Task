<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurement extends Model
{
    use ScopeTrait,SoftDeletes;

    const ACTIVE = 'active';
    const MONTHLY = 'monthly';
    const HOURLY = 'hourly';

    protected $fillable = ['name','full_name','code','status'];

    private $search_columns = [
        'id','name', 'status'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('full_name')){
            $query = $query->where('full_name','like','%' .  $filter . '%');
        }
        if ($filter = request('code')){
            $query = $query->where('code','like','%' .  $filter . '%');
        }
        if ($filter = request('status')){
            $query = $query->where('status','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }
}
