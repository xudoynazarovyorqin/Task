<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class Currency extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'rate'      => floatval($this->rate),
            'symbol'    => $this->symbol,
            'code'      => $this->code,
            'active'    => boolval($this->active),
            'reverse'           => boolval($this->reverse),
            'reversed_rate'      => floatval($this->reversed_rate),
        ];
    }
}
