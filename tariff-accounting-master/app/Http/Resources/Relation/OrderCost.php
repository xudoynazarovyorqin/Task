<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCost extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'cost'  => new Cost($this->cost),
            'amount' => floatval($this->amount),
            'currency' => new Currency($this->currency),
            'rate'      => floatval($this->rate)
        ];
    }
}
