<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class Assembly extends JsonResource
{
	public $withParams = false;

    public function __construct($resource, $withParams = false)
    {
        $this->resource = $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'client_name'   => ($this->assemblyable && $this->assemblyable->client) ? $this->assemblyable->client->name : '',
            'total_amount'  => ($this->assemblyable) ? $this->assemblyable->amount : 0,
            'payed_sum'     => ($this->assemblyable) ? $this->assemblyable->paid : 0,
            'percent'       => $this->percent(),
            'owner'         => $this->owner,

            $this->mergeWhen($this->withParams === true,[
                'assembly_items'  => new \App\Http\Resources\Relation\AssemblyItemCollection($this->items),
            ])
        ];
    }
}
