<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Realization extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->resource = $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            \App\Realization::ABLE_TYPE => $this[\App\Realization::ABLE_TYPE],
            \App\Realization::ABLE_ID   => $this[\App\Realization::ABLE_ID],
            'user'  => new \App\Http\Resources\Relation\User($this->user),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'realization_materials' => new \App\Http\Resources\Relation\RealizationMaterialCollection($this->realization_materials)
            ])
        ];
    }
}
