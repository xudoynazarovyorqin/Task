<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Realization extends Model implements Auditable
{
    use SoftDeletes;
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    const ABLE_TYPE = 'realizationable_type';
    const ABLE_ID = 'realizationable_id';
    const TABLE_NAME = 'realizations';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,'user_id','datetime'];

    private $search_columns = [];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('datetime')){
            $query = $query->whereDate('datetime','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
        }
        if ($filter = request('realizationable_type')){
            $query = $query->where('realizationable_type', $filter);
        }
        if ($filter = request('realizationable_id')){
            $query = $query->where('realizationable_id', $filter);
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

    public function realizationable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function realization_materials(){
        return $this->hasMany(RealizationMaterial::class);
    }

    public function scopeAble($query,$table,$id){
        return $query->where(self::ABLE_TYPE,$table)
            ->where(self::ABLE_ID,$id);
    }
}
