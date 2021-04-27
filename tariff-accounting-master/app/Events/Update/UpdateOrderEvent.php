<?php

namespace App\Events\Update;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;

    protected $client_id;

    protected $state_id;

    protected $number;

    protected $datetime;

    protected $products;

    protected $priority_id;

    protected $costs;

    protected $additional_materials;

    protected $employees;

    protected $new;

    protected $order;

    protected $production_type;

    private $begin_date;

    private $end_date;

    private $contract_client_id;


    public function __construct()
    {
        $this->request = null;
        $this->employees = null;
        $this->products = null;
        $this->additional_materials = null;
        $this->costs = null;
        $this->new = true;
        $this->order = null;
        $this->number = null;
        $this->datetime = null;
        $this->production_type = '';
        $this->client_id = null;
        $this->priority_id = null;
        $this->state_id = null;
        $this->begin_date = null;
        $this->end_date = null;
        $this->contract_client_id = null;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return null
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param null $client_id
     */
    public function setClientId($client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * @return null
     */
    public function getStateId()
    {
        return $this->state_id;
    }

    /**
     * @param null $state_id
     */
    public function setStateId($state_id): void
    {
        $this->state_id = $state_id;
    }

    /**
     * @return null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param null $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
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
    public function getPriorityId()
    {
        return $this->priority_id;
    }

    /**
     * @param null $priority_id
     */
    public function setPriorityId($priority_id): void
    {
        $this->priority_id = $priority_id;
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
     * @return string
     */
    public function getProductionType(): string
    {
        return $this->production_type;
    }

    /**
     * @param string $production_type
     */
    public function setProductionType(string $production_type): void
    {
        $this->production_type = $production_type;
    }

    /**
     * @return null
     */
    public function getBeginDate()
    {
        return $this->begin_date;
    }

    /**
     * @param null $begin_date
     */
    public function setBeginDate($begin_date): void
    {
        $this->begin_date = $begin_date;
    }

    /**
     * @return null
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param null $end_date
     */
    public function setEndDate($end_date): void
    {
        $this->end_date = $end_date;
    }

    /**
     * @return null
     */
    public function getContractClientId()
    {
        return $this->contract_client_id;
    }

    /**
     * @param null $contract_client_id
     */
    public function setContractClientId($contract_client_id): void
    {
        $this->contract_client_id = $contract_client_id;
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
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param mixed $costs
     */
    public function setCosts($costs): void
    {
        $this->costs = $costs;
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

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

}
