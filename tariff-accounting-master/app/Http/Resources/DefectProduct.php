<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DefectProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new Relation\Product($this->product),
            'date' => $this->date,
            'quantity' => floatval($this->quantity),
        ];
    }
}
