<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BuyProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\BuyProduct';

    public function toArray($request)
    {
        return $this->collection;
    }
}