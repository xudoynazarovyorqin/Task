<?php

namespace App\Events\Reservation;

use App\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReservationMaterialForSaleEevent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Sale
     */
    private $sale;

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
