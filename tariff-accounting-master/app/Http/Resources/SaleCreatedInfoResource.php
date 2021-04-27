<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleCreatedInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'ip_address'=> $this->ip_address,
            'user_agent'=> $this->user_agent,
            'accept_language'  => $this->accept_language,
            'user' => new User($this->user),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
