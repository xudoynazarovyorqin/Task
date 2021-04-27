<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributionCost extends JsonResource
{
    public $withParams = false;

    public function __construct($resource,$withParams = false)
    {
        $this->resource =   $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'datetime'      => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'from_date'     => $this->from_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->from_date)) : '',
            'to_date'       => $this->to_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->to_date)) : '',
            'type'          => $this->type,
            'user'          => new \App\Http\Resources\Relation\User($this->user),
            'created_at'    => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'    => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'additional_prices'   => new \App\Http\Resources\Relation\AdditionalPriceCollection($this->additional_prices),
                'distribution_transactions'   => new \App\Http\Resources\Relation\DistributionTransactionCollection($this->distribution_transactions),
            ])
        ];
    }
}
