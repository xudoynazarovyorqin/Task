<?php

namespace App\Events\Models;

use App\Assembly;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateSaleForAssemblyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $assembly;

    public function __construct(Assembly $assembly)
    {
        $this->assembly = $assembly;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return Assembly
     */
    public function getAssembly(): Assembly
    {
        return $this->assembly;
    }

    /**
     * @param Assembly $assembly
     */
    public function setAssembly(Assembly $assembly): void
    {
        $this->assembly = $assembly;
    }
}
