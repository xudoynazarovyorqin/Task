<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RealizationMaterialCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\RealizationMaterial';

    public function toArray($request)
    {
        return [
            'realization_materials' => $this->collection,
        ];
    }
}
