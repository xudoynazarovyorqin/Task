<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Currency';

    public function toArray($request)
    {
        return $this->collection;
    }
}
