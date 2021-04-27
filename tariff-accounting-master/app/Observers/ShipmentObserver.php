<?php

namespace App\Observers;

use App\Shipment;

class ShipmentObserver
{
    public function creating(Shipment $shipment)
    {
    }

    public function created(Shipment $shipment)
    {
        //
    }

    public function updated(Shipment $shipment)
    {
        //
    }

    public function deleted(Shipment $shipment)
    {
        $shipment->shipment_products()->each(function ($item){
            $item->delete();
        });
    }

    public function restored(Shipment $shipment)
    {
        //
    }

    public function forceDeleted(Shipment $shipment)
    {
        //
    }
}
