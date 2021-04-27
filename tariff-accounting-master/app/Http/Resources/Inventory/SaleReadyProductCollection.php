<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleReadyProductCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\SaleReadyProduct';

    public function toArray($request)
    {
        return [
            'saleReadyProducts' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'last_page' => $this->lastPage()
            ]
        ];
    }
}
