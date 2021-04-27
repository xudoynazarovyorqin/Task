<?php

namespace Goodoneuz\PayUz\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSystemParam extends Model
{
    protected $table = 'payuz_payment_system_params';

    /**
     * @var array
     */
    protected $fillable = [
        'system',
        'label',
        'name',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment_system(){
        return $this->hasOne(PaymentSystem::class,'system','system');
    }
}
