<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuyReadyProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $number;

    protected $datetime;

    protected $provider_id;

    protected $contract_id;

    protected $status_id;

    protected $date;

    protected $comment;

    protected $is_warehouse;

    protected $object_id;

    protected $object_type;

    protected $notification_id;

    protected $new;

    protected $model;

    protected $items;

    public function __construct()
    {
        $this->provider_id = null;
        $this->date = null;
        $this->datetime = null;
        $this->number = null;
        $this->contract_id = null;
        $this->status_id = null;
        $this->comment = null;
        $this->is_warehouse = 1;
        $this->object_id = null;
        $this->object_type = null;
        $this->notification_id = null;
        $this->new = true;
        $this->model = null;
        $this->items = [];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
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
    public function getProviderId()
    {
        return $this->provider_id;
    }

    /**
     * @param null $provider_id
     */
    public function setProviderId($provider_id): void
    {
        $this->provider_id = $provider_id;
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
     * @return null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param null $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param null $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function getIsWarehouse(): int
    {
        return $this->is_warehouse;
    }

    /**
     * @param int $is_warehouse
     */
    public function setIsWarehouse(int $is_warehouse): void
    {
        $this->is_warehouse = $is_warehouse;
    }

    /**
     * @return null
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * @param null $object_id
     */
    public function setObjectId($object_id): void
    {
        $this->object_id = $object_id;
    }

    /**
     * @return null
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * @param null $object_type
     */
    public function setObjectType($object_type): void
    {
        $this->object_type = $object_type;
    }

    /**
     * @return null
     */
    public function getNotificationId()
    {
        return $this->notification_id;
    }

    /**
     * @param null $notification_id
     */
    public function setNotificationId($notification_id): void
    {
        $this->notification_id = $notification_id;
    }
}
