<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateWarehouseMaterialEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;
    /**
     * @var mixed
     */
    private $material_id;
    /**
     * @var mixed
     */
    private $remainder;
    /**
     * @var mixed
     */
    private $buy_price;
    /**
     * @var mixed
     */
    private $warehouse_id;
    /**
     * @var mixed
     */
    private $price;
    /**
     * @var mixed
     */
    private $warehouse_materialable_type;
    /**
     * @var mixed
     */
    private $warehouse_materialable_id;

    private $currency_id;

    private $rate;
    /**
     * @var double
     */
    private $booked;

    public function __construct($request = null)
    {
        $this->material_id = $request['material_id'];
        $this->remainder = $request['remainder'];
        $this->currency_id = $request['currency_id'];
        $this->rate = $request['rate'];
        $this->buy_price = $request['buy_price'];
        $this->warehouse_id = $request['warehouse_id'];
        $this->price = $request['price'];
        $this->warehouse_materialable_type = $request['warehouse_materialable_type'];
        $this->warehouse_materialable_id = $request['warehouse_materialable_id'];
        $this->booked = 0.0;
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
     * @return float
     */
    public function getBooked(): float
    {
        return $this->booked;
    }

    /**
     * @param float $booked
     */
    public function setBooked(float $booked): void
    {
        $this->booked = $booked;
    }

    /**
     * @return mixed
     */
    public function getMaterialId()
    {
        return $this->material_id;
    }

    /**
     * @param mixed $material_id
     */
    public function setMaterialId($material_id): void
    {
        $this->material_id = $material_id;
    }

    /**
     * @return mixed
     */
    public function getRemainder()
    {
        return $this->remainder;
    }

    /**
     * @param mixed $remainder
     */
    public function setRemainder($remainder): void
    {
        $this->remainder = $remainder;
    }

    /**
     * @return mixed
     */
    public function getBuyPrice()
    {
        return $this->buy_price;
    }

    /**
     * @param mixed $buy_price
     */
    public function setBuyPrice($buy_price): void
    {
        $this->buy_price = $buy_price;
    }

    /**
     * @return mixed
     */
    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    /**
     * @param mixed $warehouse_id
     */
    public function setWarehouseId($warehouse_id): void
    {
        $this->warehouse_id = $warehouse_id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getWarehouseMaterialableType()
    {
        return $this->warehouse_materialable_type;
    }

    /**
     * @param mixed $warehouse_materialable_type
     */
    public function setWarehouseMaterialableType($warehouse_materialable_type): void
    {
        $this->warehouse_materialable_type = $warehouse_materialable_type;
    }

    /**
     * @return mixed
     */
    public function getWarehouseMaterialableId()
    {
        return $this->warehouse_materialable_id;
    }

    /**
     * @param mixed $warehouse_materialable_id
     */
    public function setWarehouseMaterialableId($warehouse_materialable_id): void
    {
        $this->warehouse_materialable_id = $warehouse_materialable_id;
    }

    /**
     * @return mixed
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * @param mixed $currency_id
     */
    public function setCurrencyId($currency_id): void
    {
        $this->currency_id = $currency_id;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }
}
