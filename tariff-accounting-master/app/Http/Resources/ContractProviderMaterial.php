<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractProviderMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'material' => new Material($this->material),
            'qty' => $this->qty,
        ];
    }
}
