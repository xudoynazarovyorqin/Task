<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateWarehouseProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;
    /**
     * @var mixed
     */
    private $product_id;
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
    private $selling_price;
    /**
     * @var mixed
     */
    private $receive;
    /**
     * @var mixed
     */
    private $warehouse_productable_type;
    /**
     * @var mixed
     */
    private $warehouse_productable_id;
    /**
     * @var mixed
     */
    private $owner;
    /**
     * @var mixed
     */
    private $agentable_type;
    /**
     * @var mixed
     */
    private $agentable_id;

    private $currency_id;

    private $rate;
    /**
     * @var float
     */
    private $booked;

    public function __construct($request = null)
    {
        $this->product_id = $request['product_id'];
        $this->remainder = $request['remainder'];
        $this->currency_id = $request['currency_id'];
        $this->rate = $request['rate'];
        $this->buy_price = $request['buy_price'];
        $this->warehouse_id = $request['warehouse_id'];
        $this->selling_price = $request['selling_price'];
        $this->receive = $request['receive'];
        $this->warehouse_productable_type = $request['warehouse_productable_type'];
        $this->warehouse_productable_id = $request['warehouse_productable_id'];
        $this->owner = $request['owner'];
        $this->agentable_type = $request['agentable_type'];
        $this->agentable_id = $request['agentable_id'];
        $this->booked = 0.0;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
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


    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request): void
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id): void
    {
        $this->product_id = $product_id;
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
    public function getSellingPrice()
    {
        return $this->selling_price;
    }

    /**
     * @param mixed $selling_price
     */
    public function setSellingPrice($selling_price): void
    {
        $this->selling_price = $selling_price;
    }

    /**
     * @return mixed
     */
    public function getReceive()
    {
        return $this->receive;
    }

    /**
     * @param mixed $receive
     */
    public function setReceive($receive): void
    {
        $this->receive = $receive;
    }

    /**
     * @return mixed
     */
    public function getWarehouseProductableType()
    {
        return $this->warehouse_productable_type;
    }

    /**
     * @param mixed $warehouse_productable_type
     */
    public function setWarehouseProductableType($warehouse_productable_type): void
    {
        $this->warehouse_productable_type = $warehouse_productable_type;
    }

    /**
     * @return mixed
     */
    public function getWarehouseProductableId()
    {
        return $this->warehouse_productable_id;
    }

    /**
     * @param mixed $warehouse_productable_id
     */
    public function setWarehouseProductableId($warehouse_productable_id): void
    {
        $this->warehouse_productable_id = $warehouse_productable_id;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getAgentableType()
    {
        return $this->agentable_type;
    }

    /**
     * @param mixed $agentable_type
     */
    public function setAgentableType($agentable_type): void
    {
        $this->agentable_type = $agentable_type;
    }

    /**
     * @return mixed
     */
    public function getAgentableId()
    {
        return $this->agentable_id;
    }

    /**
     * @param mixed $agentable_id
     */
    public function setAgentableId($agentable_id): void
    {
        $this->agentable_id = $agentable_id;
    }
}
