<?php

namespace App\Http\Resources;

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
            'total_coming'      => floatval($this->total_coming),
            'remainder'         => floatval($this->remainder),
            'booked'            => floatval($this->booked),
            'buy_price'         => floatval($this->buy_price),
            'price'             => floatval($this->price),
            'additional_price'  => floatval($this->additional_prices()->sum('price')),
            'currency'          => new \App\Http\Resources\Relation\Currency($this->currency),
            'rate'              => floatval($this->rate),
            'buy_id'              => $this->buy_id,
            'is_reworked'       => floatval($this->is_reworked),
            'day_in_warehouse'  => date_diff(date_create(date('Y-m-d H:i:s')),date_create($this->created_at))->format("%a"),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
        ];
    }
}
