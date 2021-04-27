<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DefectProductCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\Relation\DefectProduct';

    public function toArray($request)
    {
        return $this->collection;
    }
}
