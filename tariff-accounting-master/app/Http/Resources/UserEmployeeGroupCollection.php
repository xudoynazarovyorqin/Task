<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserEmployeeGroupCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\UserEmployeeGroup';

    public function toArray($request)
    {
        return [
                'data' => $this->collection
            ];
    }
}
