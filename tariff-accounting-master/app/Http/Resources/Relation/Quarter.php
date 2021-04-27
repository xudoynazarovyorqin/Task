<?php


namespace App\Http\Resources\Relation;


use Illuminate\Http\Resources\Json\JsonResource;

class Quarter extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
