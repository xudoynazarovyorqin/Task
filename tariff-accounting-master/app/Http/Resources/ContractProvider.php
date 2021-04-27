<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractProvider extends JsonResource
{

    public $withParams;

    public function __construct($resource , $withParams = false)
    {
        $this->resource = $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
                'id'                    => $this->id,
                'number'                => $this->number,
                'begin_date'            => $this->begin_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->begin_date)) : '',
                'provider'              => new Relation\Provider($this->provider),
                'status'                => new Relation\State($this->status),
                'sum'                   => floatval($this->sum),
                'paid'                  => floatval($this->paid),
                'comment'               => $this->comment,
                'parent'                => $this->parent,
                'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
                'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at))
        ];
    }
}
