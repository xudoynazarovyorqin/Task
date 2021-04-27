<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateDefectProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;

    protected $reasons;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * @param mixed $reasons
     */
    public function setReasons($reasons): void
    {
        $this->reasons = $reasons;
    }
}
