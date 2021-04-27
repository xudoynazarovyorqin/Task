<?php

namespace App\Http\Resources\Relation;

use App\WarehouseProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class AssemblyItem extends JsonResource
{
	public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product'       => new Product($this->product),
            'order_product' => $this->order_product,
            'quantity'      => floatval($this->quantity),
            'ready'         => floatval($this->ready),
            'not_produced'  => floatval($this->quantity - $this->ready),
        	'defect_count'  => floatval($this->defect_products()->sum('quantity')),
        ];
    }
}
