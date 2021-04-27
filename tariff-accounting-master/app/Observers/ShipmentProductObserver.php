<?php

namespace App\Observers;

use App\Events\Back\BackProductToWarehouseFromShipmentProductEvent;
use App\Events\GetFromWarehouse\GetProductForShipmentProductEvent;
use App\ShipmentProduct;
use App\SaleReadyProductList;
use App\WarehouseProduct;

class ShipmentProductObserver
{
    public function created(ShipmentProduct $shipmentProduct)
    {
        event(new GetProductForShipmentProductEvent($shipmentProduct));
    }

    public function updated(ShipmentProduct $shipmentProduct)
    {
        //
    }

    public function deleted(ShipmentProduct $shipmentProduct)
    {
        event(new BackProductToWarehouseFromShipmentProductEvent($shipmentProduct));
    }

    public function restored(ShipmentProduct $shipmentProduct)
    {
        //
    }

    public function forceDeleted(ShipmentProduct $shipmentProduct)
    {
        //
    }
}
