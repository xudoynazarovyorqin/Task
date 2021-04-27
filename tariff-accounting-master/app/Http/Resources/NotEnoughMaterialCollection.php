<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotEnoughMaterialCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\NotEnoughMaterial';

    public function toArray($request)
    {
        return $this->collection;
    }
}
