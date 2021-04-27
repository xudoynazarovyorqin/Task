<?php


namespace App\Http\Resources\Relation;


use Illuminate\Http\Resources\Json\JsonResource;

class Service extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'price'             => floatval($this->price),
            'measurement'       => new Measurement($this->measurement),
        ];
    }
}
