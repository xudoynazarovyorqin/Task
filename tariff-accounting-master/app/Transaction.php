<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Transaction extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const ABLE_TYPE = 'transactionable_type';
    const ABLE_ID = 'transactionable_id';

    const CONTRACT_ABLE_TYPE = 'contractable_type';
    const CONTRACT_ABLE_ID = 'contractable_id';

    const TABLE_NAME = 'transactions';

    protected $fillable = [
        self::ABLE_TYPE,self::ABLE_ID, self::CONTRACT_ABLE_ID,self::CONTRACT_ABLE_TYPE,
        'payment_type_id', 'amount', 'real_amount','currency_id', 'rate', 'datetime', 'comment', 'user_id',
        'score_id', 'distribution_amount'
    ];

    private $search_columns = [
        'id', 'amount', 'rate', 'datetime', 'comment','debit', self::ABLE_TYPE
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('debit')){
            $query = $query->where('debit',$filter);
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
        if ($filter = request('datetime')){
            $query = $query->where('datetime','like','%' . $filter . '%');
        }
        if ($filter = request('comment')){
            $query = $query->where('comment','like','%' . $filter . '%');
        }
        if ($filter = request('transactionable_id')){
            $query = $query->where('transactionable_id', $filter);
        }
        if ($filter = request('contractable_id')){
            $query = $query->where('contractable_id', $filter);
        }
        if ($filter = request('distribution_amount')){
            $query = $query->where('distribution_amount','like','%' . $filter . '%');
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
            $query = $query->whereDate('datetime','>=',Carbon::parse($from)->toDateString());
        }
        if ($to = request()->get('to_date',null)){
            $query = $query->whereDate('datetime','<=',Carbon::parse($to)->toDateString());
        }
        return $query;
    }

    public function scopeCosts($query)
    {
        return $query->where(self::ABLE_TYPE, Cost::TABLE_NAME);
    }

    public function payments(){
        return $this->morphMany(Payment::class, 'sourceable');
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function contractable()
    {
        return $this->morphTo();
    }

    public function payment_type() {
        return $this->belongsTo(PaymentType::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function score(){
        return $this->belongsTo(Score::class);
    }
}
