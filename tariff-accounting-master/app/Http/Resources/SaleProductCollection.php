<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleProductCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\SaleProduct';

    public function toArray($request)
    {
        return $this->collection;
    }
}
