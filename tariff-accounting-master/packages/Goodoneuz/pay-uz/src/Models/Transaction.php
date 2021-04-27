<?php

namespace Goodoneuz\PayUz\Models;

use App\Application;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use ScopeTrait;
    use SoftDeletes;

    const TABLE_NAME = 'payuz_transactions';

    protected $table = 'payuz_transactions';

    protected $dates    = [
        'deleted_at'
    ];

    protected $casts = [
        'amount'    => 'float',
        'state'     => 'int',
        'created_at'  => 'datetime:' . Controller::ELEMENT_DATE_TIME_FORMAT,
        'updated_at'  => 'datetime:' . Controller::ELEMENT_DATE_TIME_FORMAT,
    ];

    private $search_columns = [
        'id', 'amount', 'payment_system'
    ];

    protected $fillable = [
        'payment_system', //varchar 191
        'system_transaction_id', // varchar 191
        'click_paydoc_id', // faqat Click uchun (Vozvrat qilganda Click id kereligi uchun)
        'amount', // double (15,5)
        'currency_code', // int(11)
        'state', // int(11)
        'updated_time', //datetime
        'comment', // varchar 191
        'transactionable_type',
        'transactionable_id',
        'detail', // details
    ];
    const TIMEOUT = 43200000;

    const STATE_CREATED = 1;
    const STATE_COMPLETED = 2;
    const STATE_CANCELLED = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED = 3;
    const REASON_CANCELLED_BY_TIMEOUT = 4;
    const REASON_FUND_RETURNED = 5;
    const REASON_UNKNOWN = 10;

    const CURRENCY_CODE_UZS = 860;
    const CURRENCY_CODE_RUB = 643;
    const CURRENCY_CODE_USD = 840;
    const CURRENCY_CODE_EUR = 978;

    public function cancel($reason)
    {
        $this->updated_time = DataFormat::timestamp(true);

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        $this->comment = $reason;
        $detail = json_decode($this->detail,true);
        $detail['cancel_time'] = $this->updated_time;
        $detail = json_encode($detail);
        $this->detail = $detail;



        $this->update();
    }
    public function isExpired()
    {
        return $this->state == self::STATE_CREATED && DataFormat::datetime2timestamp($this->updated_time) - time() > self::TIMEOUT;
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id',$filter);
        }
        if ($filter = request('payment_system')){
            $query = $query->where('payment_system', 'like', '%' . $filter . '%');
        }
        if ($filter = request('system_transaction_id')){
            $query = $query->where('system_transaction_id', 'like', '%' . $filter . '%');
        }
        if ($filter = request('amount')){
            $query = $query->where('amount','like','%' . $filter . '%');
        }
        if ($filter = request('state')){
            $query = $query->where('state', $filter);
        }
        if ($filter = request('comment')){
            $query = $query->where('comment','like','%' . $filter . '%');
        }
        if ($filter = request('transactionable_id')){
            $query = $query->where('transactionable_id', $filter);
        }
        if ($filter = request('district_id')){
            $query = $query->whereHasMorph('transactionable', Application::class, function (Builder $query) use ($filter){
                $query->where('district_id',$filter);
            });
        }
        if ($filter = request('client_id')){
            $query = $query->whereHasMorph('transactionable', Application::class, function (Builder $query) use ($filter){
                $query->where('client_id',$filter);
            });
        }
        if ($filter = request('console_number')){
            $query = $query->whereHasMorph('transactionable', Application::class, function (Builder $query) use ($filter){
                $query->where('console_number','like','%' . $filter . '%');
            });
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

    public function scopeIsCompleted($query)
    {
        return $query->where('state', self::STATE_COMPLETED);
    }

}
