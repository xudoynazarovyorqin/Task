<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductSemiProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\ProductSemiProduct';


    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
