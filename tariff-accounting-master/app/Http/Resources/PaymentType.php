<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentType extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'  => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'key'       => $this->key,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}