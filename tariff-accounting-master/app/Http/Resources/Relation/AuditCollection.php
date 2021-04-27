<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuditCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Audit';

    public function toArray($request)
    {
        return $this->collection;
    }
}
