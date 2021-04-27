<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class Provider extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    	=> $this->id,
            'name'  	=> $this->name,
            'balance'  	=> floatval($this->balance),
        ];
    }
}
