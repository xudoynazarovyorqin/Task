<?php

namespace App\Observers;

use App\BuyMaterial;
use App\Events\Receive\ReceiveMaterialToWarehouseFromBuyEvent;
use App\WarehouseMaterial;
use Illuminate\Support\Facades\Log;

class WarehouseMaterialObserver
{
    public function creating(WarehouseMaterial $warehouseMaterial){
        if ($buy_material = BuyMaterial::find($warehouseMaterial->warehouse_materialable_id)){
            $warehouseMaterial->buy_price  = $buy_material->price;
            $warehouseMaterial->rate  = $buy_material->rate;
            $warehouseMaterial->currency_id  = $buy_material->currency_id;
        }
    }

    public function created(WarehouseMaterial $warehouseMaterial)
    {
        if ($warehouseMaterial->warehouse_materialable_type == WarehouseMaterial::ABLE_TYPE_BUY_MATERIAL)
        {
            event(new ReceiveMaterialToWarehouseFromBuyEvent($warehouseMaterial));
        }
    }

    public function updated(WarehouseMaterial $warehouseMaterial)
    {
        //
    }

    public function deleted(WarehouseMaterial $warehouseMaterial)
    {
        //
    }

    public function restored(WarehouseMaterial $warehouseMaterial)
    {
        //
    }

    public function forceDeleted(WarehouseMaterial $warehouseMaterial)
    {
        //
    }
}
