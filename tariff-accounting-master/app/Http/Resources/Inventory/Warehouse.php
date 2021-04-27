<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class Warehouse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'show_in_nav'   => boolval($this->show_in_nav),
            'warehouse_type_id'  => floatval($this->warehouse_type_id),
        ];
    }
}
