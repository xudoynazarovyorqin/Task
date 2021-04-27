<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractClientProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new Relation\Product($this->product),
            'qty' => $this->qty,
        ];
    }
}
