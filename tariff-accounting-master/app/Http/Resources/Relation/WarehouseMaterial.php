<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'material'          => new \App\Http\Resources\Relation\Material($this->material),
            'warehouse'         => new \App\Http\Resources\Relation\Warehouse($this->warehouse),
            'remainder'         => floatval($this->remainder),
            'booked'            => floatval($this->booked),
            'buy_price'         => floatval($this->buy_price),
            'rate'              => floatval($this->rate),
            'warehouse_materialable_type' => $this->warehouse_materialable_type,
            'currency'          => new Currency($this->currency),
            'created_at'        => $this->created_at ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->created_at)) : '',
        ];
    }
}
