<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateSaleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;

    protected $products;

    protected $additional_materials;

    protected $employees;

    protected $new;

    protected $sale;

    public function __construct()
    {
        $this->request = null;
        $this->products = null;
        $this->additional_materials = null;
        $this->employees = null;
        $this->new = true;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * @param bool $new
     */
    public function setNew(bool $new): void
    {
        $this->new = $new;
    }


    /**
     * @return mixed
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * @param mixed $sale
     */
    public function setSale($sale): void
    {
        $this->sale = $sale;
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
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getAdditionalMaterials()
    {
        return $this->additional_materials;
    }

    /**
     * @param mixed $additional_materials
     */
    public function setAdditionalMaterials($additional_materials): void
    {
        $this->additional_materials = $additional_materials;
    }

    /**
     * @return mixed
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @param mixed $employees
     */
    public function setEmployees($employees): void
    {
        $this->employees = $employees;
    }


}
