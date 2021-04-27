<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AssemblyResource extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Stock\Assembly';

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
