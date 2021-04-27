<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleReadyProductItem extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'product'   => new \App\Http\Resources\Relation\Product($this->product),
            'quantity'  => floatval($this->quantity),
            'selling_price' => floatval($this->selling_price),
            'currency'  => new \App\Http\Resources\Relation\Currency($this->currency),
            'rate'      => floatval($this->rate)
        ];
    }
}
