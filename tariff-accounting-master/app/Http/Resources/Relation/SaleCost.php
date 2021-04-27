<?php

namespace App\Http\Resources\Relation;

use App\Http\Resources\Cost;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleCost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'cost'   => new Cost($this->cost),
            'amount'      => floatval($this->amount),
        ];
    }
}
