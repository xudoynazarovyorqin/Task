<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Payment extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'payments';

    const ABLE_TYPE = 'paymentable_type';
    const ABLE_ID = 'paymentable_id';

    const SOURCEABLE_TYPE = 'sourceable_type';
    const SOURCEABLE_ID = 'sourceable_id';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,self::SOURCEABLE_ID,self::SOURCEABLE_TYPE,'amount'];

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function scopeDocuments($query){
        return $query->where(function($query){
            $query->orWhere(self::ABLE_TYPE,Buy::TABLE_NAME)
            ->orWhere(self::ABLE_TYPE,BuyReadyProduct::TABLE_NAME)
            ->orWhere(self::ABLE_TYPE,SaleReadyProduct::TABLE_NAME)
            ->orWhere(self::ABLE_TYPE,Order::TABLE_NAME);
        });
    }
}
