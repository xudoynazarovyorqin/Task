<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Provider extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'full_name'     => $this->full_name,
            'sku'           => $this->sku,
            'phone'         => $this->phone,
            'fax'           => $this->fax,
            'email'         => $this->email,
            'comment'       => $this->comment,
            'actual_address' => $this->actual_address,
            'legal_address'  => $this->legal_address,
            'type'          => $this->type(),
            'inn'       => $this->inn,
            'mfo'       => $this->mfo,
            'okonx'     => $this->okonx,
            'oked'      => $this->oked,
            'rkp_nds'   => $this->rkp_nds,
            'balance'   => floatval($this->balance),
            'total_buy' => floatval($this->buys()->sum('total_price') + $this->buy_ready_products()->sum('total_price')),
            'total_buy_paid' => floatval($this->buys()->sum('paid_price') + $this->buy_ready_products()->sum('paid_price')),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams,[
                'provider_checking_accounts' => $this->provider_checking_accounts,
                'provider_contact_persons'   => $this->provider_contact_persons,
            ])
        ];
    }
}
