<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;

class BuyReadyProductNotification extends Model
{
	use ScopeTrait;

	const ABLE_ID = 'buy_ready_product_notificationable_id';
	const ABLE_TYPE = 'buy_ready_product_notificationable_type';

    const CREATED = 'created';

    const WAITING = 'waiting';

    const COMPLETED = 'completed';

    const CANCELED = 'canceled';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,'body','status'];

    public function scopeFilter($query){
        if ($filter = request('id')){
            $query = $query->where('id','like','%'. $filter . '%');
        }
        if ($filter = request('buy_ready_product_notificationable_id')){
            $query = $query->where('buy_ready_product_notificationable_id', $filter);
        }
        if ($filter = request('buy_ready_product_notificationable_type')){
            $query = $query->where('buy_ready_product_notificationable_type', $filter);
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

    public function products(){
        return $this->hasMany(NotEnoughProduct::class, 'buy_ready_product_notification_id');
    }
}
