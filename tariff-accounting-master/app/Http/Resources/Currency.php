<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Currency extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'active'    => boolval($this->active),
            'name'      => $this->name,
            'rate'      => floatval($this->rate),
            'symbol'            => $this->symbol,
            'reverse'           => boolval($this->reverse),
            'reversed_rate'      => floatval($this->reversed_rate),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
