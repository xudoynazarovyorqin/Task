<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractClientSuspenseCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\ContractClientSuspense';

    public function toArray($request)
    {
        return $this->collection;
    }
}
