<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Shipment extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const ABLE_ID = 'shipmentable_id';
    const ABLE_TYPE = 'shipmentable_type';
    const TABLE_NAME = 'shipments';

    protected $fillable = [
		self::ABLE_ID,self::ABLE_TYPE , 'datetime', 'comment', 'user_id'
    ];

    private $search_columns = [
        'comment', 'user_id', 'datetime'
    ];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id',$filter );
        }
        if ($filter = request(self::ABLE_TYPE)){
            $query = $query->where(self::ABLE_TYPE,'like','%'. $filter . '%');
        }
        if ($filter = request(self::ABLE_ID)){
            $query = $query->where(self::ABLE_TYPE, $filter);
        }
        if ($filter = request('comment')){
            $query = $query->where('comment', 'like','%'. $filter . '%');
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
        }
        if ($from = request()->get('datetime',null)){
            $query = $query->whereDate('datetime','>=',Carbon::parse($from)->toDateString());
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('datetime','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('datetime','<=',Carbon::parse($to)->toDateString());
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        return $query;
    }

    public function shipment_products(){
        return $this->hasMany(ShipmentProduct::class);
    }

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function shipmentable()
    {
        return $this->morphTo();
    }
}
