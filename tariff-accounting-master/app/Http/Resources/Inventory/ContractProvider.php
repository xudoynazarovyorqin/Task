<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractProvider extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'number'                => $this->number,
            'begin_date'            => ($this->begin_date)  ? date('d.m.Y',strtotime($this->begin_date)) : '',
            'provider_id'           => $this->provider_id,
            'sum'                   => floatval($this->sum),
            'remainder'             => floatval($this->remainder),
        ];
    }
}
