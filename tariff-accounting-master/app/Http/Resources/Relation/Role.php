<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->withParams = $withParams;
        $this->resource = $resource;
    }

    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            $this->mergeWhen($this->withParams === true,[
                'permissions'   => new PermissionCollection($this->permissions),
            ])
        ];
    }
}
