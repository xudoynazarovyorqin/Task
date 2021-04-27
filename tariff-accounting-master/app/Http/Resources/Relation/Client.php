<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'full_name' => $this->full_name,
            'sku'       => $this->sku,
            'phone'     => $this->phone,
            'email'     => $this->email,
            'actual_address'     => $this->actual_address,
        ];
    }
}
