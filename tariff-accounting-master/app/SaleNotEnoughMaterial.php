<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SaleNotEnoughMaterial extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['sale_id','sale_product_id','material_id','quantity','sale_not_enough_material_notification_id'];

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

    public function sale_product(){
        return $this->belongsTo(SaleProduct::class);
    }

    public function material(){
        return $this->belongsTo(Material::class)->withTrashed();
    }
}
