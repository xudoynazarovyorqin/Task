<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public $collects = 'App\Http\Resources\Relation\Client';

    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
