<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\WarehouseProduct';

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
