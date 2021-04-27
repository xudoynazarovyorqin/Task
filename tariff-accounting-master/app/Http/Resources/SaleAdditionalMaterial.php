<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleAdditionalMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'sale'       => new Sale($this->sale),
            'material'   => new Material($this->material),
            'quantity'      => floatval($this->quantity),
        ];
    }
}
