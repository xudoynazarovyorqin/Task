<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BuyReadyProductItemCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\BuyReadyProductItem';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
