<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class NotEnoughMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'material'      => new Relation\Material($this->material),
            'quantity'      => floatval($this->quantity),            
        ];
    }
}
