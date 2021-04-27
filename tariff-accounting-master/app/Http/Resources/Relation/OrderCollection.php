<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
