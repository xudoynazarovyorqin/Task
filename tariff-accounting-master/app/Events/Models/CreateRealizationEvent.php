<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateRealizationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    protected $user_id;

    protected $datetime;

    protected $realizationable_type;

    protected $realizationable_id;

    protected $items;

    public function __construct()
    {
        $this->user_id = null;
        $this->datetime = null;
        $this->realizationable_id = null;
        $this->realizationable_type = null;
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
    public function getRealizationableType()
    {
        return $this->realizationable_type;
    }

    /**
     * @param null $realizationable_type
     */
    public function setRealizationableType($realizationable_type): void
    {
        $this->realizationable_type = $realizationable_type;
    }

    /**
     * @return null
     */
    public function getRealizationableId()
    {
        return $this->realizationable_id;
    }

    /**
     * @param null $realizationable_id
     */
    public function setRealizationableId($realizationable_id): void
    {
        $this->realizationable_id = $realizationable_id;
    }
}
