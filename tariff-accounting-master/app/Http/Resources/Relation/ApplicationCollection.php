<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Application';

    public function toArray($request) {
        return $this->collection;
    }
}
