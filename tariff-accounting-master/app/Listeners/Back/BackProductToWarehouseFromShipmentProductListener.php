<?php

namespace App\Listeners\Back;

use App\Events\Back\BackProductToWarehouseFromShipmentProductEvent;
use App\OutputProduct;
use App\ShipmentProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackProductToWarehouseFromShipmentProductListener
{
    public function __construct()
    {
        //
    }

    public function handle(BackProductToWarehouseFromShipmentProductEvent $event)
    {
        if ($shipment_product = $event->getShipmentProduct()){
            /**
             *
             */
            $output_products = OutputProduct::where([
                [OutputProduct::ABLE_ID,'=',$shipment_product->id],
                [OutputProduct::ABLE_TYPE,'=',ShipmentProduct::TABLE_NAME]
            ])->get();

            foreach ($output_products as $output_product){
                $output_product->delete();
            }
        }

    }
}
