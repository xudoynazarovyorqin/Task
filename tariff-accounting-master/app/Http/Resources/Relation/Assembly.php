<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Assembly extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'state'             => new State($this->state),
            'priority'          => $this->auditable_id,
            'begin_date'        => date(Controller::EXCEL_DATE_FORMAT,$this->begin_date),
            'end_date'          => date(Controller::EXCEL_DATE_FORMAT,$this->end_date),
        ];
    }
}
