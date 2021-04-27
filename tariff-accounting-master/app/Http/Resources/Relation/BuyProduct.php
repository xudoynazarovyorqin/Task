<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class BuyProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'product'           => new Product($this->product),
            'qty_weight'        => floatval($this->qty_weight),
            'buy_price'         => floatval($this->buy_price),
            'selling_price'     => floatval($this->selling_price),
            'not_enough'        => floatval($this->not_enough),
            'currency'          => new Currency($this->currency),
            'rate'              => floatval($this->rate),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
