<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Category';

    public function toArray($request)
    {
        return $this->collection;
    }
}
