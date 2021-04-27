<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Sale extends JsonResource
{
    public function toArray($request)
    {
        return [
                'id'                => $this->id,
                'owner'             => $this->owner,
                'state'             => new State($this->state),
                'priority'          => new Priority($this->priority),
                'begin_date'        => $this->begin_date ? date(Controller::DATE_FORMAT,strtotime($this->begin_date)) : '',
                'end_date'          => $this->end_date ? date(Controller::DATE_FORMAT,strtotime($this->end_date)) : '',
                'level'             => new Level($this->level)
            ];
    }
}
