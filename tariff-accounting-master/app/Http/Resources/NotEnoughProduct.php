<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class NotEnoughProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product'       => new Relation\Product($this->product),
            'quantity'      => floatval($this->quantity),            
        ];
    }
}
