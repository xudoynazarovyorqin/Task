<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Score extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'branch_name'   => $this->branch_name,
            'mfo'           => $this->mfo,
            'number'        => $this->number,
            'active'        => boolval($this->active),
            'incoming'      => floatval($this->incoming),
            'outgoing'      => floatval($this->outgoing),
            'currency'      => new Relation\Currency($this->currency),
        ];
    }
}
