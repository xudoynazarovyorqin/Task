<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'user'              =>   new User($this->user),
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            'body'              => $this->body
        ];
    }
}
