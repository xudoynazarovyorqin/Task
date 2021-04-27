<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseReportCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\WarehouseReport';

    public function toArray($request)
    {
        return [
            'warehouseProducts' => $this->collection,
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
