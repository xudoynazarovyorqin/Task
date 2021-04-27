<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractClient extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'number'                => $this->number,
            'begin_date'            => ($this->begin_date) ? date('d.m.Y',strtotime($this->begin_date)) : '',
            'conclusion_date'       => ($this->conclusion_date) ? date(Controller::ELEMENT_DATE_FORMAT, strtotime($this->conclusion_date)) : '',
            'termination_date'      => ($this->termination_date) ? date(Controller::ELEMENT_DATE_FORMAT, strtotime($this->termination_date)) : '',
            'sum'                   => floatval($this->sum),
            'remainder'             => floatval($this->remainder),
        ];
    }
}
