<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotEnoughProductCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\NotEnoughProduct';

    public function toArray($request)
    {
        return $this->collection;
    }
}
