<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
                'code'          => $this->code,
                'recycled'      => $this->recycled,
                'measurement'   => new \App\Http\Resources\Relation\Measurement($this->measurement),
                'purchase_price'=> floatval($this->purchase_price),
                'purchase_currency' => new \App\Http\Resources\Relation\Currency($this->purchase_currency),
                'selling_price' => floatval($this->selling_price),
                'selling_currency'   => new \App\Http\Resources\Relation\Currency($this->selling_currency),
                'vendor_code'   => $this->vendor_code,
                'country'       => new \App\Http\Resources\Relation\Country($this->country),
                'warehouse_type'=> new \App\Http\Resources\Relation\WarehouseType($this->warehouse_type),
                'nds'           => $this->nds,
                'description'   => $this->description,
                'production'    => floatval($this->production),
                'production_type'   => $this->production_type,
                'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
                'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
                $this->mergeWhen($this->withParams === true,[
                    'materials' => new ProductMaterialCollection($this->materials),
                    'categories' => new Relation\CategoryCollection($this->categories),
                    'semi_products' => new ProductSemiProductCollection($this->semi_products)
                ])
        ];
    }
}
