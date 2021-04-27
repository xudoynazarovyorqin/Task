<?php

namespace App\Http\Resources\Inventory;

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
