<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssemblyAdditionalMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\AssemblyAdditionalMaterial';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
