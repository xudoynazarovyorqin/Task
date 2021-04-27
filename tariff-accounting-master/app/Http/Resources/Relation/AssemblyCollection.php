<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssemblyCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Assembly';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
