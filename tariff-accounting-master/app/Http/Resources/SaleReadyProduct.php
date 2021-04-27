<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleReadyProduct extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->resource =   $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'number'            => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'user'              => new \App\Http\Resources\Relation\User($this->user),
            'client'            => new Relation\Client($this->client),
            'contract_client'   => new \App\Http\Resources\Relation\ContractClient($this->contract_client),
            'total_price'       => floatval($this->total_price),
            'paid_price'         => floatval($this->paid_price),
            'state'             => new \App\Http\Resources\Relation\State($this->state),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'items'     => new SaleReadyProductItemCollection($this->items)
            ])
        ];
    }
}
