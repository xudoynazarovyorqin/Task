<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShipmentProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\ShipmentProduct';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
