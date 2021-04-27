<?php

namespace App\Listeners\Update;

use App\Events\Update\UpdateShipmentEvent;
use App\Shipment;
use App\ShipmentProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateShipmentListener
{

    public function __construct()
    {
        //
    }

    public function handle(UpdateShipmentEvent $event)
    {

        if ($shipment = $event->getShipment()){

            $shipment->shipment_products()->each(function ($item){
                $item->delete();
            });

            $shipment->update([
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
}
