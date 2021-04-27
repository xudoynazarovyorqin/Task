<?php


namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Quarter extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'quarters';

    protected $fillable = [
        'district_id','name','description',
    ];

    protected $search_columns = ['id','district_id','name','description'];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('district_id')){
            $query = $query->where('district_id',$filter);
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' . $filter . '%');
        }
        if ($filter = request('description')){
            $query = $query->where('description','like','%' . $filter . '%');
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
