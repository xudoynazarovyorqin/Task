<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Material';

    public function toArray($request)
    {
        return $this->collection;
    }
}
