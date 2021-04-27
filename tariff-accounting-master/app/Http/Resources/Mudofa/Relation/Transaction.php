<?php


namespace App\Http\Resources\Mudofa\Relation;


use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'payment_system'    => $this->payment_system,
            'amount'            => floatval($this->amount),
            'state'             => intval($this->state),
            'created_at'        => $this->created_at ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->created_at)) : '',
        ];
    }
}
