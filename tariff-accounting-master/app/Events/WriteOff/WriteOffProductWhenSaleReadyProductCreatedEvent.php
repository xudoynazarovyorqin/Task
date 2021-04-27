<?php

namespace App\Events\WriteOff;

use App\SaleReadyProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WriteOffProductWhenSaleReadyProductCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $sale;

    public function __construct(SaleReadyProduct $sale)
    {
        $this->sale = $sale;
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
     * @return SaleReadyProduct
     */
    public function getSale(): SaleReadyProduct
    {
        return $this->sale;
    }

    /**
     * @param SaleReadyProduct $sale
     */
    public function setSale(SaleReadyProduct $sale): void
    {
        $this->sale = $sale;
    }
}
