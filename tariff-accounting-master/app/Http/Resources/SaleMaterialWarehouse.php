<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleMaterialWarehouse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'material' => new Material($this->material),
            'warehouse_material' => new WarehouseMaterial($this->warehouse_material),
            'quantity' => floatval($this->quantity),
            'back'   => floatval($this->back)
        ];
    }
}
