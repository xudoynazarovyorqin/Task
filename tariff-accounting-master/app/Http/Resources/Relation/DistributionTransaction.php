<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class DistributionTransaction extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'distribution_cost'         => $this->distribution_cost,
            'transaction'               => new Transaction($this->transaction),
            'transaction_id'            => ($this->transaction) ? $this->transaction->id : null,
            'price'                     => floatval($this->price),
            'distributioning_amount'    => floatval($this->price),
        ];
    }
}
