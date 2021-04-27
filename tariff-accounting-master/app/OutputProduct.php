<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class OutputProduct extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes;
    use Auditable;

    const ABLE_ID = 'output_productable_id';
    const ABLE_TYPE = 'output_productable_type';

    const SOURCEABLE_ID = 'sourceable_id';
    const SOURCEABLE_TYPE = 'sourceable_type';

    const OUTPUT_PRODUCT_TYPE_ASSEMBLIES = 'assemblies';
    const OUTPUT_PRODUCT_TYPE_SALE = 'sales';
    const OUTPUT_PRODUCT_TYPE_SALE_READY_PRODUCT_ITEM = 'sale_ready_product_items';

    protected $fillable = [self::ABLE_ID,self::ABLE_TYPE,self::SOURCEABLE_TYPE,self::SOURCEABLE_ID,'product_id','warehouse_product_id','quantity','back'];

    public function warehouse_product(){
        return $this->belongsTo(WarehouseProduct::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function output_productable()
    {
        return $this->morphTo();
    }

    public function sourceable()
    {
        return $this->morphTo();
    }
}
