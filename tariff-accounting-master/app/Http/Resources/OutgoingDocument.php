<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class OutgoingDocument extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'paymentable_type'  => $this->paymentable_type,
            'paymentable_id'    => $this->paymentable_id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'total_amount'      => floatval($this->total_amount),
            'paid_amount'       => floatval($this->paid_amount),
            'state'             => new Relation\State($this->state),
            'contract_client'   => new Relation\ContractClient($this->contract_client),
        ];
    }
}
