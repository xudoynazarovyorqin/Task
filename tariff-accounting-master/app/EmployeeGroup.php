<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeGroup extends Model implements Auditable
{
    use ScopeTrait, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name','description'];

    private $search_columns = ['id','name','description'];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%'. $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
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

    public function users(){
        return $this->belongsToMany(User::class,'employee_group_users')->withTrashed();
    }
}
