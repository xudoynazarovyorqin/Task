<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RealizationMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\RealizationMaterial';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
