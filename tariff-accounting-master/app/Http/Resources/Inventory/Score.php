<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class Score extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'active'        => boolval($this->active),
            'incoming'      => floatval($this->incoming),
            'outgoing'      => floatval($this->outgoing),
            'currency'      => new \App\Http\Resources\Relation\Currency($this->currency),
        ];
    }
}
