<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationServiceCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\ApplicationService';

    public function toArray($request) {
        return $this->collection;
    }
}
