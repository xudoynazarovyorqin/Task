<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Payment';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
