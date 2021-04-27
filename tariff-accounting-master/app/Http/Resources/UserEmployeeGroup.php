<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEmployeeGroup extends JsonResource
{
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user'          => $this->user,
            'employee_group'=> $this->employee_group,
        ];
    }
}
