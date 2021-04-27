<?php

namespace App;

use App\Http\Controllers\Traits\ScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ShipmentProduct extends Model implements Auditable
{
    use ScopeTrait;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    const TABLE_NAME = 'shipment_products';

    protected $fillable = [
		'shipment_id', 'product_id', 'quantity', 'issued_from_booked'
    ];

    private $search_columns = [
        'quantity'
    ];

    public function shipment(){
        return $this->belongsTo(Shipment::class)->withTrashed();
    }

    public function product() {
		return $this->belongsTo(Product::class)->withTrashed();
	}
}
