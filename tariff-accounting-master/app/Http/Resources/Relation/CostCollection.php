<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CostCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Cost';

    public function toArray($request)
    {
        return $this->collection;
    }
}
