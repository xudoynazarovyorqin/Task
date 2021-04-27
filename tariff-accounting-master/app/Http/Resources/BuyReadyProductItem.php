<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BuyReadyProductItem extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'qty_weight'    => floatval($this->qty_weight),
            'product'       => new \App\Http\Resources\Relation\Product($this->product),
            'rate'          => floatval($this->rate),
            'currency'      => new \App\Http\Resources\Relation\Currency($this->currency),
            'buy_price'     => floatval($this->buy_price),
            'selling_price' => floatval($this->selling_price),
            'not_enough'    => floatval($this->not_enough),
        ];
    }
}
