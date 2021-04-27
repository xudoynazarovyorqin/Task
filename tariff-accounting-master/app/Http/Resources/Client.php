<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'inn'           => $this->inn,
            'mfo'           => $this->mfo,
            'okonx'         => $this->okonx,
            'oked'          => $this->oked,
            'rkp_nds'       => $this->rkp_nds,
            'object_name'       => $this->object_name,
            'district'          => new Relation\District($this->district),
            'quarter'           => new Relation\Quarter($this->quarter),
            'object_street'     => $this->object_street,
            'object_home'       => $this->object_home,
            'object_corps'      => $this->object_corps,
            'object_flat'       => $this->object_flat,
            'balance'       => floatval($this->balance),
            'total_sale_sum' => floatval($this->orders()->sum('amount') + $this->sale_ready_products()->sum('total_price')),
            'total_sale_sum_paid' => floatval($this->orders()->sum('paid') + $this->sale_ready_products()->sum('paid_price')),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'client_checking_accounts' => $this->client_checking_accounts,
                'client_contact_persons'   => $this->client_contact_persons,
                'sales'                    => [],//new SaleResource($this->orders()->paginate(1000000)),
                'sale_ready_products'      => new SaleReadyProductCollection($this->sale_ready_products()->paginate(1000000)),
            ])
        ];
    }
}
