<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class State extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'status'=> $this->status,
        ];
    }
}
