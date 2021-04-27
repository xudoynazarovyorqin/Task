<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractClientProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\ContractClientProduct';

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
