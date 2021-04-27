<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssemblyItemCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\Relation\AssemblyItem';

    public function toArray($request)
    {
        return  $this->collection;
    }

}
