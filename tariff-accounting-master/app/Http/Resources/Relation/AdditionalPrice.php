<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalPrice extends JsonResource
{
    public function toArray($request)
    {
        $additional_priceable = null;

        switch ($this->additional_priceable_type){
            case \App\WarehouseProduct::TABLE_NAME:
                $additional_priceable = new WarehouseProduct($this->additional_priceable); break;
            case \App\WarehouseMaterial::TABLE_NAME:
                $additional_priceable = new WarehouseMaterial($this->additional_priceable); break;
        }

        return [
            'id'                        => $this->id,
            'additional_priceable_type' => $this->additional_priceable_type,
            'additional_priceable_id'   => $this->additional_priceable_id,
            'additional_priceable'      => $additional_priceable,
            'price'                     => floatval($this->price),
        ];
    }
}
