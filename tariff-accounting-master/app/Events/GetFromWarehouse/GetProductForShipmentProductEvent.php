<?php

namespace App\Events\GetFromWarehouse;

use App\ShipmentProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GetProductForShipmentProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ShipmentProduct
     */
    private $shipment_product;

    public function __construct(ShipmentProduct $shipment_product)
    {
        $this->shipment_product = $shipment_product;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return ShipmentProduct
     */
    public function getShipmentProduct(): ShipmentProduct
    {
        return $this->shipment_product;
    }

    /**
     * @param ShipmentProduct $shipment_product
     */
    public function setShipmentProduct(ShipmentProduct $shipment_product): void
    {
        $this->shipment_product = $shipment_product;
    }
}
