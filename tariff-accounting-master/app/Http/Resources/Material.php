<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Material extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'code'              => $this->code,
            'sku'               => $this->sku,
            'qty_weight'        => floatval($this->qty_weight),
            'is_active'         => $this->is_active,
            'price'             => floatval($this->price),
            'price_currency'    => new \App\Http\Resources\Relation\Currency($this->price_currency),
            'critical_weight'   => $this->critical_weight,
            'is_reworking'      => $this->is_reworking,
            'measurement_changeable'     => boolval($this->measurement_changeable),
            'additional_measurement'     => new Relation\Measurement($this->additional_measurement),
            'additional_measurement_rate'=> $this->additional_measurement_rate,
            'country'           => new Relation\Country($this->country),
            'measurement'       => new Relation\Measurement($this->measurement),
            'warehouse_type'    => new Relation\WarehouseType($this->warehouse_type),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
