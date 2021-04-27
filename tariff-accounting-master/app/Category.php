<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use ScopeTrait, SoftDeletes;

    protected $fillable = ['name','slug','parent_id'];

    private $search_columns = ['id','name','slug','parent_id'];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%'. $filter . '%');
        }
        if ($filter = request('parent_id')){
            $query = $query->where('parent_id','like','%'. $filter . '%');
        }
        if ($filter = request('slug')){
            $query = $query->where('slug','like','%'. $filter . '%');
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
    public function parent(){
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function products(){
        return $this->hasMany(Product::class)->withTrashed();
    }
}
