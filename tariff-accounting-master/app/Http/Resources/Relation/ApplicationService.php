<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationService extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'service'   => new Service($this->service),
            'price'     => floatval($this->price),
        ];
    }
}
