<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StateCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\State';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
