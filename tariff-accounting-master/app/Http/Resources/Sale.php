<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Sale extends JsonResource
{
    public $withParams = false;

    public function __construct($resource,$withParams = false)
    {
        $this->resource = $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        $products = $this->products();
        return [
            'id'                => $this->id,
            'number'            => $this->number,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'owner'             => $this->owner,
            'saleable_id'       => $this->saleable_id,
            'saleable_type'     => $this->saleable_type,
            'state'             => new \App\Http\Resources\Relation\State($this->state),
            'priority'          => new \App\Http\Resources\Relation\Priority($this->priority),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'begin_date'        => $this->begin_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->begin_date)) : '',
            'end_date'          => $this->end_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->end_date)) : '',
            'parent_id'         => $this->parent_id,
            'is_child'          => $this->is_child,
            'reservation_of'    => boolval($this->reservation_of),
            'product_name'      => $products->first() ? $products->first()->product ? $products->first()->product->name : '' : '',
            'level'             => new \App\Http\Resources\Relation\Level($this->level),
            'total_products'    => floatval($products->sum('quantity')),
            'ready_products'    => floatval($products->sum('ready')),
            $this->mergeWhen($this->withParams === true,[
                'sale_products' => new Relation\SaleProductCollection($this->products),
                'histories'     => new SaleHistoryCollection($this->histories),
                'created_info'  => new SaleCreatedInfoResource($this->created_info),
                'additional_materials' => new \App\Http\Resources\Relation\SaleAdditionalMaterialCollection($this->additional_materials),
            ])
        ];
    }
}
