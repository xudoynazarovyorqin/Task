<?php

namespace App\Http\Resources;

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
        $transaction = null;
        $contract = null;

        switch ($this->transactionable_type){
            case \App\Client::TABLE_NAME:
                $transaction = new Relation\Client($this->transactionable); break;
            case \App\Provider::TABLE_NAME:
                $transaction = new Relation\Provider($this->transactionable); break;
            case \App\Cost::TABLE_NAME:
                $transaction = new Relation\Cost($this->transactionable); break;
        }

        switch ($this->contractable_type){
            case \App\ContractClient::TABLE_NAME:
                $contract = new Relation\ContractClient($this->contractable); break;
            case \App\ContractProvider::TABLE_NAME:
                $contract = new Relation\ContractProvider($this->contractable);break;
        }

        return [
            'id'                    => $this->id,
            'debit'                 => intval($this->debit),
            'payment_type'          => new Relation\PaymentType($this->payment_type),
            'datetime'              => $this->datetime ? date(Controller::ELEMENT_DATE_TIME_FORMAT,strtotime($this->datetime)) : '',
            'transaction'           => $transaction,
            'contract'              => $contract,
            'amount'                => floatval($this->amount),
            'distribution_amount'   => floatval($this->distribution_amount),
            'currency'              => new Relation\Currency($this->currency),
            'score'                 => new Relation\Score($this->score),
            'rate'                  => floatval($this->rate),
            'comment'               => $this->comment,
            'user'                  => new Relation\User($this->user),
            'created_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at'            => date(Controller::EXCEL_DATE_FORMAT,strtotime($this->updated_at)),
            $this->mergeWhen($this->withParams === true,[
                'payments' => new PaymentCollection($this->payments()->documents()->get())
            ])
        ];
    }
}
