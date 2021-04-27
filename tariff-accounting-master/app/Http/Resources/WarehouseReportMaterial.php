<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseReportMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'code'            => $this->code,
            'sku'             => $this->sku,
            'critical_weight' => $this->critical_weight,
            'measurement_changeable'     => $this->measurement_changeable,
            'additional_measurement'     => new Relation\Measurement($this->additional_measurement),
            'additional_measurement_rate'=> $this->additional_measurement_rate,
            'measurement'     => new Relation\Measurement($this->measurement),
            'remainder'       => floatval($this->warehouse_materials()->where('remainder','>',0)->sum('remainder')),
            'booked'       => floatval($this->warehouse_materials()->where('remainder','>',0)->sum('booked')),
            'total_buy_price'=> floatval($this->warehouse_materials()->where('remainder','>',0)->selectRaw('sum(buy_price * rate * remainder) as total_buy_price')->get()->sum('total_buy_price')),
            'total_price'=> floatval($this->warehouse_materials()->where('remainder','>',0)->selectRaw('sum(price * rate * remainder) as total_price')->get()->sum('total_price')),
        ];
    }
}
