<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class RealizationMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'material'      => new Material($this->material),
            'quantity'      => floatval($this->quantity),
            'issued_from_booked'  => floatval($this->issued_from_booked),
            'available'     => floatval($this->material->warehouse_materials()->sum('remainder') + $this->quantity),
            'booked'        => floatval($this->material->warehouse_materials()->sum('booked') + $this->issued_from_booked),
        ];
    }
}
