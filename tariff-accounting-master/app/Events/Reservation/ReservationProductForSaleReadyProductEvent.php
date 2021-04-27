<?php

namespace App\Events\Reservation;

use App\SaleReadyProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReservationProductForSaleReadyProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var SaleReadyProduct
     */
    private $saleReadyProduct;

    public function __construct(SaleReadyProduct $saleReadyProduct)
    {
        $this->saleReadyProduct = $saleReadyProduct;
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
    public function getSaleReadyProduct(): SaleReadyProduct
    {
        return $this->saleReadyProduct;
    }

    /**
     * @param SaleReadyProduct $saleReadyProduct
     */
    public function setSaleReadyProduct(SaleReadyProduct $saleReadyProduct): void
    {
        $this->saleReadyProduct = $saleReadyProduct;
    }
}
