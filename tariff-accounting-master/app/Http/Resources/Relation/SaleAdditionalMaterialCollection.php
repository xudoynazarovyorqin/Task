<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleAdditionalMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\SaleAdditionalMaterial';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
