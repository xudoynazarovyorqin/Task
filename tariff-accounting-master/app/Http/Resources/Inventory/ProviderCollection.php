<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\Provider';

    public function toArray($request)
    {
        return [
                'providers' => $this->collection,
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