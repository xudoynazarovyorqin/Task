<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OutgoingDocumentCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\OutgoingDocument';

    public function toArray($request)
    {
        return $this->collection;
    }
}
