<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
//            'object_name'       => $this->object_name,
//            'district'          => new \App\Http\Resources\Relation\District($this->district),
//            'quarter'           => new \App\Http\Resources\Relation\Quarter($this->quarter),
//            'object_street'     => $this->object_street,
//            'object_home'       => $this->object_home,
//            'object_corps'      => $this->object_corps,
//            'object_flat'       => $this->object_flat,
        ];
    }
}
