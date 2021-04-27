<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Service extends Model implements Auditable
{
    use ScopeTrait,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'price', 'measurement_id'];

    public $search_columns = ['name', 'price'];

    public function scopeFilter($query)
    {
        if( $filter = \request('name') ) {
            $query = $query->where('name', 'ilike', '%' . $filter . '%');
        }
        if( $filter = \request('price') ) {
            $query = $query->where('price', 'like', '%' . $filter . '%');
        }
        if( $filter = \request('measurement_id') ) {
            $query = $query->where('measurement_id', $filter);
        }

        return $query;
    }

    public function measurement() {
        return $this->belongsTo(Measurement::class);
    }
}
