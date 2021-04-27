<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Shipment extends JsonResource
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
                'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
                'shipmentable_type' => $this->shipmentable_type,
                'shipmentable_id'   => $this->shipmentable_id,
                'comment'           => $this->comment,
                'user'              => new \App\Http\Resources\Relation\User($this->user),
                'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
                'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
                $this->mergeWhen($this->withParams === true,[
                  'shipment_products' => new \App\Http\Resources\Relation\ShipmentProductCollection($this->shipment_products)
                ])
        ];
    }
}
