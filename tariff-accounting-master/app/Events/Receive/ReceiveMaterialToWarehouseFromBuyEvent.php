<?php

namespace App\Events\Receive;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReceiveMaterialToWarehouseFromBuyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $warehouse_material;

    public function __construct($warehouse_material)
    {
        $this->warehouse_material = $warehouse_material;
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
    public function getWarehouseMaterial()
    {
        return $this->warehouse_material;
    }

    /**
     * @param mixed $warehouse_material
     */
    public function setWarehouseMaterial($warehouse_material): void
    {
        $this->warehouse_material = $warehouse_material;
    }
}
