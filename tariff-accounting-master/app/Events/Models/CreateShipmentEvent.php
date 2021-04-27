<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateShipmentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var null
     */
    private $shipmentable_type;
    /**
     * @var null
     */
    private $shipmentable_id;
    /**
     * @var null
     */
    private $user_id;
    /**
     * @var array
     */
    private $items;
    /**
     * @var null
     */
    private $datetime;
    /**
     * @var null
     */
    private $comment;

    public function __construct()
    {
        $this->shipmentable_type = null;
        $this->shipmentable_id = null;
        $this->user_id = null;
        $this->datetime = null;
        $this->comment = null;
        $this->items = [];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
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
     * @return null
     */
    public function getShipmentableType()
    {
        return $this->shipmentable_type;
    }

    /**
     * @param null $shipmentable_type
     */
    public function setShipmentableType($shipmentable_type): void
    {
        $this->shipmentable_type = $shipmentable_type;
    }

    /**
     * @return null
     */
    public function getShipmentableId()
    {
        return $this->shipmentable_id;
    }

    /**
     * @param null $shipmentable_id
     */
    public function setShipmentableId($shipmentable_id): void
    {
        $this->shipmentable_id = $shipmentable_id;
    }

    /**
     * @return null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param null $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }
}
