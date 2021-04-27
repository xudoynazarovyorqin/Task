<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product'       => new Product($this->product),
            'price'         => floatval($this->price),
            'quantity'      => floatval($this->quantity),
            'ready'         => floatval($this->ready),
            'not_produced'  => floatval($this->quantity - $this->ready),
            'defects_count' => floatval($this->defect_products()->sum('quantity')),
        ];
    }
}
