<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Buy extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'datetime'          => $this->datetime  ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'provider'          => new Provider($this->provider),
            'total_amount'      => floatval($this->total_price),
            'paid_amount'       => floatval($this->paid_price),
            'status'            => new State($this->status),
            'contract'          => new ContractProvider($this->contract_provider),
        ];
    }
}
