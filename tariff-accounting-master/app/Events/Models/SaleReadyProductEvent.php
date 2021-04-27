<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class SaleReadyProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    protected $number;

    protected $datetime;

    protected $client_id;

    protected $contract_id;

    protected $status_id;

    protected $items;

    protected $new;

    protected $model;

    public function __construct()
    {
        $this->status_id = null;
        $this->items = [];
        $this->new = true;
        $this->client_id = null;
        $this->contract_id = null;
        $this->datetime = null;
        $this->number = null;
        $this->model = null;
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
     * @return null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param null $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * @param bool $new
     */
    public function setNew(bool $new): void
    {
        $this->new = $new;
    }

    /**
     * @return null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param null $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
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
     * @return null
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param null $client_id
     */
    public function setClientId($client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * @return null
     */
    public function getContractId()
    {
        return $this->contract_id;
    }

    /**
     * @param null $contract_id
     */
    public function setContractId($contract_id): void
    {
        $this->contract_id = $contract_id;
    }

    /**
     * @return null
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @param null $status_id
     */
    public function setStatusId($status_id): void
    {
        $this->status_id = $status_id;
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
}
