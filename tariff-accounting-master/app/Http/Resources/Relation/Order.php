<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'client'            => new Client($this->client),
            'total_amount'      => floatval($this->amount),
            'paid_amount'       => floatval($this->paid),
            'state'             => new State($this->state),
            'contract'          => new ContractClient($this->contract_client),
        ];
    }
}
