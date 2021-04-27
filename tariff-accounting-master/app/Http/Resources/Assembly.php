<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Assembly extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->resource =   $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        $items = $this->items();

        return [
            'id'                => $this->id,
            'datetime'          => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'owner'             => $this->owner,
            'reservation_of'    => boolval($this->reservation_of),
            'state'             => new Relation\State($this->state),
            'priority'          => new Relation\Priority($this->priority),
            'assemblyable_id'   => $this->assemblyable_id,
            'assemblyable_type' => $this->assemblyable_type,
            'begin_date'        => $this->begin_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->begin_date)) : '',
            'end_date'          => $this->end_date ? date(Controller::ELEMENT_DATE_FORMAT,strtotime($this->end_date)) : '',
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'product_name'      => $items->first() ? $items->first()->product ? $items->first()->product->name : '' : '',
            'total_products'    => floatval($items->sum('quantity')),
            'ready_products'    => floatval($items->sum('ready')),
            $this->mergeWhen($this->withParams === true, [
                'additional_materials'  => new \App\Http\Resources\Relation\AssemblyAdditionalMaterialCollection($this->additional_materials),
                'assembly_items'  => new \App\Http\Resources\Relation\AssemblyItemCollection($this->items),
                'created_audit'   => new Audit($this->audits()->where('event','created')->first(),true),
            ])
        ];
    }
}
