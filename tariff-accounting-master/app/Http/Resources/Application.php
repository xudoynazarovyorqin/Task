<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Application extends JsonResource
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
            'id'                => $this->id,
            'number'            => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'client'            => new Relation\Client($this->client),
            'contract_client'   => new Relation\ContractClient($this->contract_client),
            'status'            => new Relation\State($this->status),
            'console_number'    => $this->console_number,
            'amount'            => floatval($this->amount),
            'object_name'       => $this->object_name,
            'district'          => new Relation\District($this->district),
            'quarter'           => new Relation\Quarter($this->quarter),
            'object_street'     => $this->object_street,
            'object_home'       => $this->object_home,
            'object_corps'      => $this->object_corps,
            'object_flat'       => $this->object_flat,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'application_services' => new \App\Http\Resources\Relation\ApplicationServiceCollection($this->services),
                //'audits' => new \App\Http\Resources\Relation\AuditCollection($this->audits),
            ])
        ];
    }
}
