<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class BuyMaterial extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'material'          => new Material($this->material),
            'qty_weight'        => floatval($this->qty_weight),
            'price'             => floatval($this->price),
            'not_enough'        => floatval($this->not_enough),
            'currency'          => new Currency($this->currency),
            'rate'              => floatval($this->rate),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
