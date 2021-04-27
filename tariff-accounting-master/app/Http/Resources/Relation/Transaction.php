<?php

namespace App\Http\Resources\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    public function toArray($request)
    {
        $transaction = null;

        switch ($this->transactionable_type){
            case \App\Cost::TABLE_NAME:
                $transaction = new Cost($this->transactionable); break;
        }

        return [
            'id'                    => $this->id,
            'payment_type'          => new PaymentType($this->payment_type),
            'datetime'              => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'transaction'           => $transaction,
            'amount'                => floatval($this->amount),
            'real_amount'           => floatval($this->real_amount),
            'distribution_amount'   => floatval(($this->distribution_amount)),
            'currency'              => new Currency($this->currency),
            'rate'                  => floatval($this->rate),
            'user'                  => new User($this->user),
            'created_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
