<?php

namespace App\Events\Output;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OutputProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var null
     */
    private $output_productable_type;
    /**
     * @var null
     */
    private $output_productable_id;
    /**
     * @var null
     */
    private $product_id;
    /**
     * @var null
     */
    private $warehouse_product_id;
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
        $this->output_productable_type = null;
        $this->output_productable_id = null;
        $this->product_id = null;
        $this->warehouse_product_id = null;
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
    public function getOutputProductableType()
    {
        return $this->output_productable_type;
    }

    /**
     * @param null $output_productable_type
     */
    public function setOutputProductableType($output_productable_type): void
    {
        $this->output_productable_type = $output_productable_type;
    }

    /**
     * @return null
     */
    public function getOutputProductableId()
    {
        return $this->output_productable_id;
    }

    /**
     * @param null $output_productable_id
     */
    public function setOutputProductableId($output_productable_id): void
    {
        $this->output_productable_id = $output_productable_id;
    }

    /**
     * @return null
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param null $product_id
     */
    public function setProductId($product_id): void
    {
        $this->product_id = $product_id;
    }

    /**
     * @return null
     */
    public function getWarehouseProductId()
    {
        return $this->warehouse_product_id;
    }

    /**
     * @param null $warehouse_product_id
     */
    public function setWarehouseProductId($warehouse_product_id): void
    {
        $this->warehouse_product_id = $warehouse_product_id;
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
}
