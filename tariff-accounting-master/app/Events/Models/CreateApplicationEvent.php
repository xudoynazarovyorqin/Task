<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateApplicationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $number;
    protected $datetime;
    protected $client_id;
    protected $contract_client_id;
    protected $status_id;
    protected $console_number;
    protected $object_name;
    protected $district_id;
    protected $quarter_id;
    protected $object_street;
    protected $object_home;
    protected $object_corps;
    protected $object_flat;
    protected $services;



    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->number = null;
        $this->datetime = null;
        $this->client_id = null;
        $this->contract_client_id = null;
        $this->status_id = null;
        $this->console_number = null;
        $this->object_name = null;
        $this->district_id = null;
        $this->quarter_id = null;
        $this->object_street = null;
        $this->object_home = null;
        $this->object_corps = null;
        $this->object_flat = null;
        $this->services = null;
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
     * @return null
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @param null $status_id
     */
    public function setStatusId($status_id): void
    {
        $this->status_id = $status_id;
    }

    /**
     * @return null
     */
    public function getConsoleNumber()
    {
        return $this->console_number;
    }

    /**
     * @param null $console_number
     */
    public function setConsoleNumber($console_number): void
    {
        $this->console_number = $console_number;
    }

    /**
     * @return null
     */
    public function getObjectName()
    {
        return $this->object_name;
    }

    /**
     * @param null $object_name
     */
    public function setObjectName($object_name): void
    {
        $this->object_name = $object_name;
    }

    /**
     * @return null
     */
    public function getDistrictId()
    {
        return $this->district_id;
    }

    /**
     * @param null $district_id
     */
    public function setDistrictId($district_id): void
    {
        $this->district_id = $district_id;
    }

    /**
     * @return null
     */
    public function getQuarterId()
    {
        return $this->quarter_id;
    }

    /**
     * @param null $quarter_id
     */
    public function setQuarterId($quarter_id): void
    {
        $this->quarter_id = $quarter_id;
    }

    /**
     * @return null
     */
    public function getObjectStreet()
    {
        return $this->object_street;
    }

    /**
     * @param null $object_street
     */
    public function setObjectStreet($object_street): void
    {
        $this->object_street = $object_street;
    }

    /**
     * @return null
     */
    public function getObjectHome()
    {
        return $this->object_home;
    }

    /**
     * @param null $object_home
     */
    public function setObjectHome($object_home): void
    {
        $this->object_home = $object_home;
    }

    /**
     * @return null
     */
    public function getObjectCorps()
    {
        return $this->object_corps;
    }

    /**
     * @param null $object_corps
     */
    public function setObjectCorps($object_corps): void
    {
        $this->object_corps = $object_corps;
    }

    /**
     * @return null
     */
    public function getObjectFlat()
    {
        return $this->object_flat;
    }

    /**
     * @param null $object_flat
     */
    public function setObjectFlat($object_flat): void
    {
        $this->object_flat = $object_flat;
    }

    /**
     * @return null
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param null $services
     */
    public function setServices($services): void
    {
        $this->services = $services;
    }

}
