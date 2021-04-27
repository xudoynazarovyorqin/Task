<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class ContractClientSuspense extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'from_date'         => $this->from_date ? date(Controller::ELEMENT_DATE_FORMAT, strtotime($this->from_date)) : '',
            'to_date'           => $this->to_date ? date(Controller::ELEMENT_DATE_FORMAT, strtotime($this->to_date)) : '',
            'comment'           => $this->comment,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
