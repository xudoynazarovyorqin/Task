<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LevelCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\Level';

    public function toArray($request)
    {
        return [
            'levels' => $this->collection
        ];
    }
}
