<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Reservation extends JsonResource
{
    public function toArray($request)
    {
        $source = null;
        $material = null;
        $available = 0;
        $booked = 0;

        switch ($this[\App\Reservation::SOURCEABLE_TYPE]){
            case \App\WarehouseMaterial::TABLE_NAME:
                $material = \App\Material::find($this->sourceable ? $this->sourceable->material_id : null);
                $available = $material->warehouse_materials()->sum('remainder');
                $booked = $material->warehouse_materials()->sum('booked');
                $source = new \App\Http\Resources\Relation\Material($material);
                break;

            case \App\WarehouseProduct::TABLE_NAME:
                $product = \App\Product::find($this->sourceable ? $this->sourceable->product_id : null);
                $available = $product->warehouse_products()->sum('remainder');
                $booked = $product->warehouse_products()->sum('booked');
                $source = new \App\Http\Resources\Relation\Product($product);
                break;
        };

        return [
            'id'            => $this->id,
            'quantity'      => floatval($this->quantity),
            'issued'        => floatval($this->issued),
            \App\Reservation::ABLE_TYPE    => $this[\App\Reservation::ABLE_TYPE],
            \App\Reservation::ABLE_ID    => $this[\App\Reservation::ABLE_ID],
            \App\Reservation::SOURCEABLE_TYPE    => $this[\App\Reservation::SOURCEABLE_TYPE],
            \App\Reservation::SOURCEABLE_ID    => $this[\App\Reservation::SOURCEABLE_ID],
            'source'       => $source,
            'available'    => floatval($available),
            'booked'       => floatval($booked)
        ];
    }
}
