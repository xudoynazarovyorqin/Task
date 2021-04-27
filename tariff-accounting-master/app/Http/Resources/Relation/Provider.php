<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Provider extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'actual_address'    => $this->actual_address,
        ];
    }
}
