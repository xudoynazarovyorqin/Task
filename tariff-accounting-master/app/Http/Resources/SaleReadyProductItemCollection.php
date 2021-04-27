<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleReadyProductItemCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\SaleReadyProductItem';

    public function toArray($request)
    {
        return $this->collection;
    }
}
