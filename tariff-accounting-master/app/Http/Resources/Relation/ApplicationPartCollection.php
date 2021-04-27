<?php


namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationPartCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\ApplicationPart';

    public function toArray($request) {
        return $this->collection;
    }
}
