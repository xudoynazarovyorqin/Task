<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\OrderProduct';

    public function toArray($request)
    {
        return $this->collection;
    }
}
