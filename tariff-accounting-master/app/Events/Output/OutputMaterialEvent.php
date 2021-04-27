<?php

namespace App\Events\Output;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OutputMaterialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var null
     */
    private $output_mateterialable_type;
    /**
     * @var null
     */
    private $output_mateterialable_id;
    /**
     * @var null
     */
    private $material_id;
    /**
     * @var null
     */
    private $warehouse_material_id;
    /**
     * @var float
     */
    private $quantity;
    /**
     * @var null
     */
    private $sourceable_type;
    /**
     * @var null
     */
    private $sourceable_id;

    public function __construct()
    {
        $this->output_mateterialable_type = null;
        $this->output_mateterialable_id = null;
        $this->material_id = null;
        $this->warehouse_material_id = null;
        $this->quantity = 0.0;
        $this->sourceable_type = null;
        $this->sourceable_id = null;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return null
     */
    public function getSourceableType()
    {
        return $this->sourceable_type;
    }

    /**
     * @param null $sourceable_type
     */
    public function setSourceableType($sourceable_type): void
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

    /**
     * @return null
     */
    public function getOutputMateterialableType()
    {
        return $this->output_mateterialable_type;
    }

    /**
     * @param null $output_mateterialable_type
     */
    public function setOutputMateterialableType($output_mateterialable_type): void
    {
        $this->output_mateterialable_type = $output_mateterialable_type;
    }

    /**
     * @return null
     */
    public function getOutputMateterialableId()
    {
        return $this->output_mateterialable_id;
    }

    /**
     * @param null $output_mateterialable_id
     */
    public function setOutputMateterialableId($output_mateterialable_id): void
    {
        $this->output_mateterialable_id = $output_mateterialable_id;
    }

    /**
     * @return null
     */
    public function getMaterialId()
    {
        return $this->material_id;
    }

    /**
     * @param null $material_id
     */
    public function setMaterialId($material_id): void
    {
        $this->material_id = $material_id;
    }

    /**
     * @return null
     */
    public function getWarehouseMaterialId()
    {
        return $this->warehouse_material_id;
    }

    /**
     * @param null $warehouse_material_id
     */
    public function setWarehouseMaterialId($warehouse_material_id): void
    {
        $this->warehouse_material_id = $warehouse_material_id;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }
}
