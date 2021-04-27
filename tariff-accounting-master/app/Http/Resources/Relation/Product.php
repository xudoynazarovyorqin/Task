<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'code'              => $this->code,
            'measurement'       => new Measurement($this->measurement),
            'purchase_price'=> floatval($this->purchase_price),
            'purchase_currency' => new \App\Http\Resources\Relation\Currency($this->purchase_currency),
            'selling_price' => floatval($this->selling_price),
            'selling_currency'   => new \App\Http\Resources\Relation\Currency($this->selling_currency),
            'minimum_price'     => floatval($this->minimum_price),
            'minimum_balance'   => $this->minimum_balance,
            'production_type'   => $this->production_type,
            'warehouse_type_id' => $this->warehouse_type_id,
        ];
    }
}
