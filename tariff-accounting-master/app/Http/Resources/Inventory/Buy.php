<?php

namespace App\Http\Resources\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\ContractProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class Buy extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'provider'          => new \App\Http\Resources\Relation\Provider($this->provider),
            'total_price'       => floatval($this->total_price),
            'paid_price'        => floatval($this->paid_price),
            'paid'              => boolval($this->paid),
            'contract_provider' => new ContractProvider($this->contract_provider),
            'status'            => new \App\Http\Resources\Relation\State($this->status),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
        ];
    }
}
