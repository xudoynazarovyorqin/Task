<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdditionalPrice extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'additional_prices';

    const ABLE_ID = 'additional_priceable_id';
    const ABLE_TYPE = 'additional_priceable_type';

    const ABLE_TYPE_WAREHOUSE_PRODUCTS = 'warehouse_products';
    const ABLE_TYPE_WAREHOUSE_MATERIALS = 'warehouse_materials';

    protected $fillable = ['distribution_cost_id', self::ABLE_ID,self::ABLE_TYPE, 'price'];


    public function additional_priceable()
    {
        return $this->morphTo();
    }
}
