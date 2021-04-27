<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;

class BuyNotification extends Model
{
	use ScopeTrait;
    use SoftDeletes;

    const ABLE_TYPE = 'buy_notificationable_type';
    const ABLE_ID = 'buy_notificationable_id';

    const CREATED = 'created';

    const WAITING = 'waiting';

    const COMPLETED = 'completed';

    const CANCELED = 'canceled';

    const SALE_TYPE = 'sales';
    const ASSEMBLY_TYPE = 'assemblies';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,'body','status'];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('buy_notificationable_id')){
            $query = $query->where('buy_notificationable_id', $filter);
        }
        if ($filter = request('buy_notificationable_type')){
            $query = $query->where('buy_notificationable_type', $filter);
        }
        if ($filter = request('body')){
            $query = $query->where('body','like','%'. $filter . '%');
        }
        if ($filter = request('status')){
            $query = $query->where('status', $filter);
        }
        if ($filter = request('created_at')){
            $query = $query->whereDate('created_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($filter = request('updated_at')){
            $query = $query->whereDate('updated_at','=',Carbon::parse($filter)->toDateString());
        }
        if ($from = request()->get('from_date',null)){
            $query = $query->whereDate('created_at','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('created_at','<=',Carbon::parse($to)->toDateString());
        }
        return $query;
    }

    public function materials(){
        return $this->hasMany(NotEnoughMaterial::class, 'buy_notification_id');
    }
}
