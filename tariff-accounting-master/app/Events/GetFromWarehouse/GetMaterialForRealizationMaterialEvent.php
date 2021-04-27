<?php

namespace App\Events\GetFromWarehouse;

use App\RealizationMaterial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GetMaterialForRealizationMaterialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var RealizationMaterial
     */
    private $realization_material;

    public function __construct(RealizationMaterial $realization_material)
    {
        $this->realization_material = $realization_material;
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
     * @return RealizationMaterial
     */
    public function getRealizationMaterial(): RealizationMaterial
    {
        return $this->realization_material;
    }

    /**
     * @param RealizationMaterial $realization_material
     */
    public function setRealizationMaterial(RealizationMaterial $realization_material): void
    {
        $this->realization_material = $realization_material;
    }
}
