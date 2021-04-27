<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Permission extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'slug', 'object_name', 'object_id', 'parent_id'];

    private $search_columns = [
        'id', 'name', 'slug', 'object_name', 'object_id', 'parent_id'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('slug')){
            $query = $query->where('slug','like','%'. $filter . '%');
        }
        if ($filter = request('parent_id')){
            $query = $query->where('parent_id', $filter);
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function parent(){
        return $this->belongsTo(Permission::class);
    }
    public function children(){
        return $this->hasMany(Permission::class,'parent_id','id');
    }
}
