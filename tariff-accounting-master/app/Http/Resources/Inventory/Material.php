<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Relation\Measurement;

class Material extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'price'                     => floatval($this->price),
            'price_currency'            => new \App\Http\Resources\Relation\Currency($this->price_currency),
            'is_reworking'              => floatval($this->is_reworking),
            'measurement_changeable'    => boolval($this->measurement_changeable),
            'additional_measurement'    => new Measurement($this->additional_measurement),
            'additional_measurement_rate'=> $this->additional_measurement_rate,
            'measurement'       => new Measurement($this->measurement),
            'warehouse_type'    => new \App\Http\Resources\Relation\WarehouseType($this->warehouse_type),
        ];
    }
}
