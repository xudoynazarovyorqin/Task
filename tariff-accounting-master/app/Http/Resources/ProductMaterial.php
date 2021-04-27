<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'material' => new Material($this->material),
            'quantity' => floatval($this->quantity),
            'inverse_quantity' => floatval($this->inverse_quantity),
        ];
    }
}
