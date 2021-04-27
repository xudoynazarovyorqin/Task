<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAuthLog extends Model
{
    use ScopeTrait;
    use SoftDeletes;

    protected $fillable = ['user_id', 'ip_address', 'status'];

    private $search_columns = [
        'id', 'user_id', 'ip_address', 'status'
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
        }
        if ($filter = request('ip_address')){
            $query = $query->where('ip_address','like','%' . $filter . '%');
        }
        if ($filter = request('status')){
            $query = $query->where('status', $filter);
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('created_at','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('created_at','<=',Carbon::parse($to)->toDateString());
        }
        return $query;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
