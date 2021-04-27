<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Audit extends JsonResource
{
    private $witParams;

    public function __construct($resource, $witParams = false)
    {
        $this->resource = $resource;
        $this->witParams = $witParams;
    }


    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'username'          => $this->user ? $this->user->name : "",
            'user_id'           => $this->user_id,
            'user_agent'        => $this->user_agent,
            'event'             => $this->event,
            'auditable_type'    => $this->auditable_type,
            'auditable_id'      => $this->auditable_id,
            'ip_address'        => $this->ip_address,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->witParams === true,[
                'user'              => new User($this->user),
            ])
        ];
    }
}
