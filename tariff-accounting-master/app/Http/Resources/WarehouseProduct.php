<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
                'id'                        => $this->id,
                'product'                   => new \App\Http\Resources\Relation\Product($this->product),
                'warehouse_productable_id'  => $this->warehouse_productable_id,
                'warehouse_productable_type'=> $this->warehouse_productable_type,
                'warehouse'                 => new \App\Http\Resources\Relation\Warehouse($this->warehouse),
                'receive'                   => floatval($this->receive),
                'remainder'                 => floatval($this->remainder),
                'booked'                    => floatval($this->booked),
                'currency'                  => new \App\Http\Resources\Relation\Currency($this->currency),
                'rate'                      => floatval($this->rate),
                'buy_price'                 => floatval($this->buy_price),
                'selling_price'             => floatval($this->selling_price),
                'additional_price'          => floatval($this->additional_prices()->sum('price')),
                'day_in_warehouse'          => date_diff(date_create(date('Y-m-d H:i:s')),date_create($this->created_at))->format("%a"),
                'created_at'                => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
        ];
    }
}
