<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RealizationMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'material'      => new \App\Http\Resources\Relation\Material($this->material),
            'quantity'      => floatval($this->quantity),
            'issued_from_booked'  => floatval($this->issued_from_booked),
        ];
    }
}
