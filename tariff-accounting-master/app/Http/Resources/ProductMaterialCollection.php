<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\ProductMaterial';


    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
