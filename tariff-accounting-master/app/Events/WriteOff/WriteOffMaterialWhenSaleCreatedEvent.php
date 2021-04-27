<?php

namespace App\Events\WriteOff;

use App\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WriteOffMaterialWhenSaleCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return Sale
     */
    public function getSale(): Sale
    {
        return $this->sale;
    }

    /**
     * @param Sale $sale
     */
    public function setSale(Sale $sale): void
    {
        $this->sale = $sale;
    }
}
