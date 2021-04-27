<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Relation\Measurement;

class Product extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'production'    => floatval($this->production),
            'production_type'=> $this->production_type,
            'measurement'   => new Measurement($this->measurement),
            'purchase_price'=> floatval($this->purchase_price),
            'purchase_currency' => new \App\Http\Resources\Relation\Currency($this->purchase_currency),
            'selling_price' => floatval($this->selling_price),
            'selling_currency'   => new \App\Http\Resources\Relation\Currency($this->selling_currency),
        ];
    }
}
