<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceHistory extends Model
{
    use ScopeTrait;
    use SoftDeletes;

    const ABLE_TYPE = 'balance_historyable_type';
    const ABLE_ID = 'balance_historyable_id';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE, 'payment_type_id', 'amount', 'currency_id', 'rate', 'date', 'comment', 'user_id'];

    private $search_columns = [
        'id',self::ABLE_ID,self::ABLE_TYPE, 'payment_type_id', 'amount', 'currency_id', 'rate', 'date', 'comment', 'user_id'
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('balance_historyable_id')){
            $query = $query->where(self::ABLE_ID, $filter);
        }
        if ($filter = request('balance_historyable_type')){
            $query = $query->where(self::ABLE_TYPE,'like','%' . $filter . '%');
        }
        if ($filter = request('payment_type_id')){
            $query = $query->where('payment_type_id', $filter);
        }
        if ($filter = request('amount')){
            $query = $query->where('amount','like','%' . $filter . '%');
        }
        if ($filter = request('currency_id')){
            $query = $query->where('currency_id', $filter);
        }
        if ($filter = request('rate')){
            $query = $query->where('rate','like','%' . $filter . '%');
        }
        if ($filter = request('date')){
            $query = $query->where('date','like','%' . $filter . '%');
        }
        if ($filter = request('comment')){
            $query = $query->where('comment','like','%' . $filter . '%');
        }
        if ($filter = request('user_id')){
            $query = $query->where('user_id', $filter);
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

    public function balance_historyable()
    {
        return $this->morphTo();
    }

    public function payment_type() {
        return $this->belongsTo(PaymentType::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
