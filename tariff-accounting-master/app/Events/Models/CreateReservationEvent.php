<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateReservationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    private $reservationable_type;
    /**
     * @var null
     */
    private $reservationable_id;
    /**
     * @var string
     */
    private $sourceable_type;
    /**
     * @var null
     */
    private $sourceable_id;
    /**
     * @var float
     */
    private $quantity;
    /**
     * @var float
     */
    private $issued;

    public function __construct()
    {
        $this->reservationable_type = '';
        $this->reservationable_id = null;
        $this->sourceable_type = '';
        $this->sourceable_id = null;
        $this->quantity = 0.0;
        $this->issued = 0.0;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return float
     */
    public function getIssued(): float
    {
        return $this->issued;
    }

    /**
     * @param float $issued
     */
    public function setIssued(float $issued): void
    {
        $this->issued = $issued;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return null
     */
    public function getReservationableId()
    {
        return $this->reservationable_id;
    }

    /**
     * @param null $reservationable_id
     */
    public function setReservationableId($reservationable_id): void
    {
        $this->reservationable_id = $reservationable_id;
    }

    /**
     * @return string
     */
    public function getReservationableType(): string
    {
        return $this->reservationable_type;
    }

    /**
     * @param string $reservationable_type
     */
    public function setReservationableType(string $reservationable_type): void
    {
        $this->reservationable_type = $reservationable_type;
    }

    /**
     * @return null
     */
    public function getSourceableId()
    {
        return $this->sourceable_id;
    }

    /**
     * @param null $sourceable_id
     */
    public function setSourceableId($sourceable_id): void
    {
        $this->sourceable_id = $sourceable_id;
    }

    /**
     * @return string
     */
    public function getSourceableType(): string
    {
        return $this->sourceable_type;
    }

    /**
     * @param string $sourceable_type
     */
    public function setSourceableType(string $sourceable_type): void
    {
        $this->sourceable_type = $sourceable_type;
    }
}
