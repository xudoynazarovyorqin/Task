<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product'       => new \App\Http\Resources\Relation\Product($this->product),
            'quantity'      => floatval($this->quantity),
            'issued_from_booked'  => floatval($this->issued_from_booked),
            'available'     => floatval($this->product->warehouse_products()->sum('remainder') + $this->quantity),
            'booked'        => floatval($this->product->warehouse_products()->sum('booked') + $this->issued_from_booked),
        ];
    }
}
