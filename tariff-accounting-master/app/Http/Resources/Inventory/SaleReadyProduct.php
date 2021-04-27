<?php

namespace App\Http\Resources\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\State;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleReadyProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'client'            => new \App\Http\Resources\Relation\Client($this->client),
            'begin_date'        => $this->begin_date,
            'end_date'          => $this->end_date,
            'amount'            => floatval($this->total_price),
            'paid'              => floatval($this->paid_price),
            'state'             => new State($this->state),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
