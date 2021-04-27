<?php
namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuarterCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\Quarter';

    public function toArray($request)
    {
        return [
            'quarters' => $this->collection,
        ];
    }
}
