<?php

namespace App\Events\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssemblyCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $items;

    protected $additional_materials;

    protected $request;

    protected $employees;

    protected $new;

    protected $assembly;

    public function __construct()
    {
        $this->request = null;
        $this->items = null;
        $this->additional_materials = null;
        $this->employees = null;
        $this->new = true;
        $this->assembly = null;
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

    public function getAssembly()
    {
        return $this->assembly;
    }

    public function setAssembly($assembly): void
    {
        $this->assembly = $assembly;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items): void
    {
        $this->items = $items;
    }

    public function getAdditionalMaterials()
    {
        return $this->additional_materials;
    }

    public function setAdditionalMaterials($additional_materials): void
    {
        $this->additional_materials = $additional_materials;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request): void
    {
        $this->request = $request;
    }

    public function getEmployees()
    {
        return $this->employees;
    }

    public function setEmployees($employees): void
    {
        $this->employees = $employees;
    }
}
