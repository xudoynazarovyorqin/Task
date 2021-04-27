<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseType extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
        ];
    }
}
