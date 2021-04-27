<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleCreatedInfoCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\SaleCreatedHistoryResource';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sale_created_infos' => $this->collection
        ];
    }
}
