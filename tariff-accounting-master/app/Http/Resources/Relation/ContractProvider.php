<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractProvider extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'number'                => $this->number,
            'begin_date'            => ($this->begin_date) ? date('d.m.Y',strtotime($this->begin_date)) : '',
            'sum'                   => floatval($this->sum),
            'remainder'             => floatval($this->remainder),
        ];
    }
}
