<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyReadyProduct extends JsonResource
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
            'datetime'          => $this->datetime  ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'provider'          => new \App\Http\Resources\Relation\Provider($this->provider),
            'date'              => $this->date  ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->date)) : '',
            'total_price'       => floatval($this->total_price),
            'paid_price'        => floatval($this->paid_price),
            'comment'           => $this->comment,
            'status'            => new \App\Http\Resources\Relation\State($this->status),
            'object_id'         => $this->object_id,
            'object_type'       => $this->object_type,
            'buy_notification_id'=> $this->buy_notification_id,
            'paid'              => boolval($this->paid),
            'is_warehouse'      => boolval($this->is_warehouse),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'contract_provider' => new Relation\ContractProvider($this->contract_provider),
            'user'              => new \App\Http\Resources\Relation\User($this->user),
            'items_count'       => floatval($this->buyProducts()->sum('qty_weight')),
            'waiting_items_count' => floatval($this->buyProducts()->sum('not_enough')),
            $this->mergeWhen($this->withParams === true,[
                'buy_products'      => new BuyReadyProductItemCollection($this->buyProducts),
            ])
        ];
    }
}