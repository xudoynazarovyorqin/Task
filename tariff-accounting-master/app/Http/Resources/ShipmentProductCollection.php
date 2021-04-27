<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShipmentProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\ShipmentProduct';

    public function toArray($request)
    {
        return [
            'shipment_products' => $this->collection,
        ];
    }
}
