<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Sale';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
