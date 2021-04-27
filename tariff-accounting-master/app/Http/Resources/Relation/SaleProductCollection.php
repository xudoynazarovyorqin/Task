<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\SaleProduct';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
