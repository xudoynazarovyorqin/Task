<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssemblyItemCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\AssemblyItem';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
