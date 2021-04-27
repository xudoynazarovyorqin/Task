<?php

namespace App\Listeners\Models;

use App\Events\Models\CreateShipmentEvent;
use App\Shipment;
use App\ShipmentProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateShipmentListener
{
    public function __construct()
    {
        //
    }

    public function handle(CreateShipmentEvent $event)
    {
        $shipment = Shipment::create([
           Shipment::ABLE_ID => $event->getShipmentableId(),
           Shipment::ABLE_TYPE => $event->getShipmentableType(),
           'comment' => $event->getComment(),
           'datetime' => $event->getDatetime(),
           'user_id' => $event->getUserId()
        ]);

        foreach ($event->getItems() as $item){
            ShipmentProduct::create([
                'shipment_id'        => $shipment->id,
                'product_id'         => $item['product_id'],
                'quantity'           => $item['quantity'],
                'issued_from_booked' => $item['issued_from_booked'],
            ]);
        }
    }
}
