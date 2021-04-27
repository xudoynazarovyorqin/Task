<?php

namespace App\Events\Models;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreateBuyNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $model;

    public function __construct()
    {
        //
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model): void
    {
        $this->model = $model;
    }
}
