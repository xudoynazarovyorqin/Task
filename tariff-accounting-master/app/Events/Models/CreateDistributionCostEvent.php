<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateDistributionCostEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $datetime;
    protected $type;
    protected $from_date;
    protected $to_date;
    protected $items;
    protected $transactions;
    /**
     * @var null
     */

    public function __construct()
    {
        $this->datetime = null;
        $this->type = '';
        $this->from_date = null;
        $this->to_date = null;
        $this->items = [];
        $this->transactions = [];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return null
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param null $datetime
     */
    public function setDatetime($datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }


    public function getFromDate()
    {
        return $this->from_date;
    }

    public function setFromDate($from_date): void
    {
        $this->from_date = $from_date;
    }


    public function getToDate()
    {
        return $this->to_date;
    }

    public function setToDate($to_date): void
    {
        $this->to_date = $to_date;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param array $transactions
     */
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }
}
