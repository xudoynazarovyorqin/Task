<?php

namespace App\Events\Copy;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CopyMaterialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var null
     */
    private $material;

    public function __construct($material = null)
    {
        $this->material = $material;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return null
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param null $material
     */
    public function setMaterial($material): void
    {
        $this->material = $material;
    }
}
