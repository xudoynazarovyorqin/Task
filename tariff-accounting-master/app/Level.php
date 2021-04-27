<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use ScopeTrait, SoftDeletes;

    protected $fillable = ['name','left','right','color'];

    protected $search_columns = ['name','color','left','right'];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }

        if ($filter = request('name')){
            $query = $query->where('name','ilike','%' . $filter . '%');
        }

        if ($filter = request('left')){
            $query = $query->where('left','like','%' . $filter . '%');
        }

        if ($filter = request('right')){
            $query = $query->where('right','like','%' . $filter . '%');
        }

        if ($filter = request('color')){
            $query = $query->where('color','like','%' . $filter . '%');
        }

        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }

        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function left_level(){
        return $this->belongsTo(Level::class,'left','id');
    }

    public function right_level(){
        return $this->belongsTo(Level::class,'right','id');
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
