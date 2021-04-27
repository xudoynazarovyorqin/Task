<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\Score';

    public function toArray($request)
    {
        return [
                'scores' => $this->collection
            ];
    }
}
