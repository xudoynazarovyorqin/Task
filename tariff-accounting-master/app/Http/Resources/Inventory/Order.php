<?php

namespace App\Http\Resources\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Audit;
use App\Http\Resources\Relation\State;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'production_type'   => $this->production_type,
            'client'            => new \App\Http\Resources\Relation\Client($this->client),
            'amount'            => floatval($this->amount),
            'paid'            => floatval($this->paid),
            'state'             => new State($this->state),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'begin_date'        => $this->begin_date ? date(Controller::DATE_FORMAT,strtotime($this->begin_date)) : '',
            'end_date'          => $this->end_date ? date(Controller::DATE_FORMAT,strtotime($this->end_date)) : '',
        ];
    }
}
