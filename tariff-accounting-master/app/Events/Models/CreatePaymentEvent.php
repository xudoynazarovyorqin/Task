<?php

namespace App\Events\Models;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreatePaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $paymentable_type;
    private $paymentable_id;
    private $sourceable_type;
    private $sourceable_id;
    private $amount;

    public function __construct()
    {
        $this->paymentable_type = '';
        $this->paymentable_id = null;
        $this->sourceable_type = '';
        $this->sourceable_id = null;
        $this->amount = 0.0;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getPaymentableType(): string
    {
        return $this->paymentable_type;
    }

    /**
     * @param string $paymentable_type
     */
    public function setPaymentableType(string $paymentable_type): void
    {
        $this->paymentable_type = $paymentable_type;
    }

    /**
     * @return null
     */
    public function getPaymentableId()
    {
        return $this->paymentable_id;
    }

    /**
     * @param null $paymentable_id
     */
    public function setPaymentableId($paymentable_id): void
    {
        $this->paymentable_id = $paymentable_id;
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
}
