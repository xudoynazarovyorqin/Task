<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Application extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'client'            => new Client($this->client),
            'contract_client'   => new ContractClient($this->contract_client),
            'console_number'    => $this->console_number,
            'status'            => new State($this->status),
            'amount'            => floatval($this->amount),
        ];
    }
}
