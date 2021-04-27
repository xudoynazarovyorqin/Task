<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'comment'=> $this->comment,
            'level'=> new Level($this->level),
            'user'  => new User($this->user),
            'deleted_at' => $this->deleted_at,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
