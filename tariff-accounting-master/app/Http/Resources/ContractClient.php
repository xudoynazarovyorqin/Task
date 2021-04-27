<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\ContractClientSuspenseCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractClient extends JsonResource
{
    protected $withParams;

    public function __construct($resource,$withParams = false)
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
                'client'                => new Relation\Client($this->client),
                'status'                => new Relation\State($this->status),
                'sum'                   => floatval($this->sum),
                'remainder'             => floatval($this->remainder),
                'paid'                  => floatval($this->paid),
                'comment'               => $this->comment,
                'parent'                => $this->parent,
                'conclusion_date'       => $this->conclusion_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->conclusion_date)) : '',
                'termination_date'      => $this->termination_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->termination_date)) : '',
                'application'           => new Relation\Application($this->application),
                'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
                'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
                $this->mergeWhen($this->withParams === true,[
                    'suspenses' => new ContractClientSuspenseCollection($this->suspenses),
                ])
        ];
    }
}
