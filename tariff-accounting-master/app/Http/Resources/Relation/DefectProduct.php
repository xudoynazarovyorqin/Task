<?php

namespace App\Http\Resources\Relation;

use App\Http\Resources\SaleProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class DefectProduct extends JsonResource
{

    public function toArray($request)
    {
        return [
                'id' => $this->id,
                'product' => new Product($this->product),
                'quantity' => $this->quantity,
                'date' => $this->date
        ];
    }
}
