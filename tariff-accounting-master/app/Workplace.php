<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Workplace extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'type_id', 'description',
    ];

    private $search_columns = [
        'id', 'name', 'type_id', 'description',
    ];



    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' .  $filter . '%');
        }
        if ($filter = request('type_id')){
            $query = $query->where('type_id', $filter);
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%'. $filter . '%');
        }
        if ($filter = request('created_at')){
            $query = $query->where('created_at','like','%'. $filter . '%');
        }

        return $query;
    }


    public function materials() {
        return $this->hasMany('App\Material');
    }

    public function workplace_type() {
		return $this->belongsTo('App\WorkplaceType', 'type_id');
	}
}
