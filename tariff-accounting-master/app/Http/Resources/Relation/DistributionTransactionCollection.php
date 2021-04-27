<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistributionTransactionCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\DistributionTransaction';

    public function toArray($request)
    {
        return $this->collection;
    }
}
