<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PriorityCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return  $this->collection;
    }
}
