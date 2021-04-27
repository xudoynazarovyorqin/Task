<?php

namespace App\Events\Receive;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReceiveProductToWarehouseFromBuyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $warehouse_product;

    public function __construct($warehouse_product)
    {
        $this->warehouse_product = $warehouse_product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return mixed
     */
    public function getWarehouseProduct()
    {
        return $this->warehouse_product;
    }

    /**
     * @param mixed $warehouse_product
     */
    public function setWarehouseProduct($warehouse_product): void
    {
        $this->warehouse_product = $warehouse_product;
    }
}
