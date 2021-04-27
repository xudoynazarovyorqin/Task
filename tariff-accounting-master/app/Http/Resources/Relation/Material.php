<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class Material extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'price'                 => floatval($this->price),
            'price_currency'        => new Currency($this->price_currency),
            'code'                  => $this->code,
            'warehouse_type_id'     => $this->warehouse_type_id,
            'measurement'           => new Measurement($this->measurement),
            'measurement_changeable'=> boolval($this->measurement_changeable),
            'additional_measurement'=> new Measurement($this->additional_measurement),
            'additional_measurement_rate'=> floatval($this->additional_measurement_rate),
        ];
    }
}
