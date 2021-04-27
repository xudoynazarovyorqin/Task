<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\PermissionCollection;
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
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'permissions'   => new PermissionCollection($this->permissions),
            ])
        ];
    }
}
