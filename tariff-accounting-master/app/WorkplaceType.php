<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class WorkplaceType extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'description',
    ];

    private $search_columns = [
        'id', 'name', 'description',
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->where('created_at','like','%'. $filter . '%');
        }
        if ($filter = request('updated_at')){
            $query = $query->where('updated_at','like','%'. $filter . '%');
        }
        return $query;
    }

    public function workplaces() {
        return $this->hasMany('App\Workplace');
    }
}
