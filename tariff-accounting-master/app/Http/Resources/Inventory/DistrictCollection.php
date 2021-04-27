<?php
namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistrictCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Inventory\District';

    public function toArray($request)
    {
        return [
            'districts' => $this->collection,
        ];
    }
}
