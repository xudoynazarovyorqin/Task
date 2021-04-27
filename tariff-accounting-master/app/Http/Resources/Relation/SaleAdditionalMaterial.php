<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleAdditionalMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'material'   => new Material($this->material),
            'quantity'   => floatval($this->quantity),
        ];
    }
}
