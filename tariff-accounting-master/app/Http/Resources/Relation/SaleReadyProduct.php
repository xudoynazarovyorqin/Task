<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleReadyProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'client'            => new Client($this->client),
            'contract'          => new ContractClient($this->contract_client),
            'total_amount'      => floatval($this->total_price),
            'paid_amount'       => floatval($this->paid_price),
            'state'             => new State($this->state),
        ];
    }
}
