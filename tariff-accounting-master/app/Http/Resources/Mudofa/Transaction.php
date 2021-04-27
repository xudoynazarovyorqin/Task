<?php

namespace App\Http\Resources\Mudofa;

use App\Http\Controllers\Controller;
use App\Http\Resources\Relation\PaymentCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    private $withParams;

    public function __construct($resource, $withParams = false)
    {
        $this->resource =   $resource;
        $this->withParams = $withParams;
    }

    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'payment_system'        => $this->payment_system,
            'system_transaction_id' => $this->system_transaction_id,
            'click_paydoc_id'       => $this->click_paydoc_id,
            'amount'                => floatval($this->amount),
            'currency_code'         => $this->currency_code,
            'state'                 => intval($this->state),
            'updated_time'          => $this->updated_time,
            'comment'               => $this->comment,
            'detail'                => $this->detail,
            'transaction'           => new \App\Http\Resources\Relation\Application($this->transactionable),
            'created_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                //'payments' => new PaymentCollection($this->payments()->documents()->get())
            ])
        ];
    }
}
