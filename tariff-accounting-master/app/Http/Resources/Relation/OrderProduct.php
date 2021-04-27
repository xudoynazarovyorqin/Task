<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'product'   => new Product($this->product),
            'price'     => floatval($this->price),
            'quantity'  => floatval($this->quantity),
            'ready'     => floatval($this->ready),
            'currency'  => new Currency($this->currency),
            'rate'      => floatval($this->rate)
        ];
    }
}