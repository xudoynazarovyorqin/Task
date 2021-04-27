<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'total_price'       => $this->total_price,
            'paid_price'        => $this->paid_price,
            'agentable'         => $this->agentable,
            'agentable_type'    => $this->agentable_type,
            'contractable'      => $this->contractable,
            'contractable_type' => $this->contractable_type,
            'paymentable'       => $this->paymentable,
            'paymentable_type'  => $this->paymentable_type,
            'transactions'      => $this->transactions,
            'is_output'         => $this->is_output,
            'created_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'        => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
