<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationPart extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'start_date'    => $this->start_date,
            'stop_date'     => $this->stop_date,
            'amount'        => floatval($this->amount),
            'paid'          => floatval($this->paid),
            'status'        => $this->status,
        ];
    }
}
