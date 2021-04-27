<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class Score extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable,SoftDeletes,ScopeTrait;

    const ABLE_TYPE = 'scoreable_type';
    const ABLE_ID = 'scoreable_id';

    protected $fillable  = [self::ABLE_TYPE,self::ABLE_ID,'name','branch_name','active','mfo','number','currency_id','incoming','outgoing'];

    protected $search_columns  = ['name','branch_name','mfo','incoming','outgoing','number'];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
