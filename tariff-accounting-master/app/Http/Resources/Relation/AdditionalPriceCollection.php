<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdditionalPriceCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\AdditionalPrice';

    public function toArray($request)
    {
        return $this->collection;
    }
}
