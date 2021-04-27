<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Relation\Score';

    public function toArray($request)
    {
        return  $this->collection;
    }
}
