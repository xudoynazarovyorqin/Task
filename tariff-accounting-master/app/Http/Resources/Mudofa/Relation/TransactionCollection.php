<?php

namespace App\Http\Resources\Mudofa\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Mudofa\Relation\Transaction';

    public function toArray($request) {
        return $this->collection;
    }
}
