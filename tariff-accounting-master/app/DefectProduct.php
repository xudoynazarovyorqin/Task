<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DefectProduct extends Model implements Auditable
{
    protected $fillable = ['quantity','product_id','date','comment', 'defectable_id', 'defectable_type'];

    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    private $search_columns = [
        'id', 'quantity', 'date', 'defectable_id', 'defectable_type'
    ];

    public function defectable()
    {
        return $this->morphTo();
    }

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function defect_product_reasons(){
        return $this->hasMany(DefectProductReason::class,'defect_product_id');
    }

    public function reasons(){
        return $this->belongsToMany(Reason::class,'defect_product_reasons')->withTrashed();
    }

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('sale_id')){
            $query = $query->where('sale_id','like','%' . $filter . '%');
        }
        if ($filter = request('quantity')){
            $query = $query->where('quantity','like','%' . $filter . '%');
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
