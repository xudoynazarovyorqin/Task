<?php

namespace App\Events\Output\After;

use App\OutputMaterial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterOutputMaterialDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var OutputMaterial
     */
    private $output_material;

    public function __construct(OutputMaterial $output_material)
    {
        $this->output_material = $output_material;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return OutputMaterial
     */
    public function getOutputMaterial(): OutputMaterial
    {
        return $this->output_material;
    }

    /**
     * @param OutputMaterial $output_material
     */
    public function setOutputMaterial(OutputMaterial $output_material): void
    {
        $this->output_material = $output_material;
    }
}
