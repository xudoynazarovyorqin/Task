<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Service';


    public function toArray($request)
    {
        return [
            'services' => $this->collection,
        ];
    }
}
