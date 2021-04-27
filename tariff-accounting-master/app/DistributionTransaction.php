<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DistributionTransaction extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['distribution_cost_id','transaction_id', 'price'];

    public function distribution_cost(){
        return $this->belongsTo(DistributionCost::class)->withTrashed();
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class)->withTrashed();
    }
}
