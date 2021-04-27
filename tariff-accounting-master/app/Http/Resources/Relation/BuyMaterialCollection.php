<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BuyMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\BuyMaterial';

    public function toArray($request)
    {
        return $this->collection;
    }
}
