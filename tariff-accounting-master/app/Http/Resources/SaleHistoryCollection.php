<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleHistoryCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\SaleHistoryResource';

    public function toArray($request)
    {
        return $this->collection;
    }
}
