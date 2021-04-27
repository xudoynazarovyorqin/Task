<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BuyReadyProductNotificationCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\BuyReadyProductNotification';

    public function toArray($request)
    {
        return [
            'buy_notifications' => $this->collection,
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
