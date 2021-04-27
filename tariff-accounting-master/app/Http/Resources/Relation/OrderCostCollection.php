<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCostCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\OrderCost';

    public function toArray($request)
    {
        return $this->collection;
    }
}
