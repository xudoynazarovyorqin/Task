<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentType extends Model implements Auditable
{
    use ScopeTrait;
    use \OwenIt\Auditing\Auditable;

    const CASH = 'cash';
    const TRANSFER = 'transfer';
    const CARD = 'card';
    const FROM_BALANCE = 'from_balance';

    protected $fillable = ['name', 'is_active', 'is_deleted', 'key'];

    private $search_columns = [
        'id', 'name', 'is_active'
    ];

    public function scopeFilter($query)
    {
        if ($filter = request('id')){
            $query = $query->where('id','like','%' . $filter . '%');
        }
        if ($filter = request('name')){
            $query = $query->where('name','like','%' . $filter . '%');
        }
        if ($filter = request('is_active')){
            $query = $query->where('is_active', $filter);
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
