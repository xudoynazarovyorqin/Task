<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    public $withParams = false;

    public function __construct($resource,$withParams = false)
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
            'owner'             => $this->owner,
            'production_type'   => $this->production_type,
            'client'            => new Relation\Client($this->client),
            'amount'            => floatval($this->amount),
            'paid'              => floatval($this->paid),
            'state'             => new Relation\State($this->state),
            'priority'          => new Relation\Priority($this->priority),
            'is_child'          => $this->is_child,
            'contract_client'   => new Relation\ContractClient($this->contract_client),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'begin_date'        => $this->begin_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->begin_date)) : '',
            'end_date'          => $this->end_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->end_date)) : '',
            $this->mergeWhen($this->withParams === true,[
                'order_costs'   => new \App\Http\Resources\Relation\OrderCostCollection($this->costs),
                'order_products'   => new \App\Http\Resources\Relation\OrderProductCollection($this->products),
                'created_audit'   => new Audit($this->audits()->where('event','created')->first(),true),
            ])
        ];
    }
}
