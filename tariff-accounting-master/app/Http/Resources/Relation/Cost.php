<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Cost extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'  => $this->id,
            'name' => $this->name,
            'amount' => floatval($this->amount),
            'currency' => new \App\Http\Resources\Relation\Currency($this->currency),
        ];
    }
}
