<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractProviderProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\ContractProviderProduct';

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
