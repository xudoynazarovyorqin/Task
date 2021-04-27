<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product'       => new Product($this->product),
            'order_product' => $this->order_product,
            'deleted_at'    => $this->deleted_at,
            'created_at'    => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'    => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'quantity'      => floatval($this->quantity),
            'ready'         => floatval($this->ready),
            'not_produced'  => floatval($this->quantity - $this->ready),
            'defects_count' => floatval($this->defect_products()->sum('quantity')),
        ];
    }
}
