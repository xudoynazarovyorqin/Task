<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleCostCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\SaleCost';


    public function toArray($request)
    {
        return  $this->collection;
    }
}
