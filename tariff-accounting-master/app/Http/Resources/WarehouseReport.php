<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseReport extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'code'            => $this->code,
            'vendor_code'     => $this->vendor_code,
            'measurement'     => new \App\Http\Resources\Relation\Measurement($this->measurement),
            'remainder'       => floatval($this->warehouse_products()->where('warehouse_id',request()->get('warehouse_id',''))->where('remainder','>',0)->sum('remainder')),
            'booked'          => floatval($this->warehouse_products()->where('warehouse_id',request()->get('warehouse_id',''))->where('remainder','>',0)->sum('booked')),
            'total_cost_price'=> floatval($this->warehouse_products()->where('warehouse_id',request()->get('warehouse_id',''))->where('remainder','>',0)->selectRaw('sum(buy_price * remainder * rate) as total_cost_price')->get()->sum('total_cost_price')),
            'total_selling_price'=> floatval($this->warehouse_products()->where('warehouse_id',request()->get('warehouse_id',''))->where('remainder','>',0)->selectRaw('sum(selling_price * remainder * rate) as total_selling_price')->get()->sum('total_selling_price')),
        ];
    }
}
