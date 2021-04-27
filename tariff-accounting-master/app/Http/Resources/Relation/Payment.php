<?php

namespace App\Http\Resources\Relation;

use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
{
    public function toArray($request)
    {
        $paymentable = null;

        switch ($this->paymentable_type){
            case \App\Buy::TABLE_NAME:
                    $paymentable = new Buy($this->paymentable);
                break;
            case \App\BuyReadyProduct::TABLE_NAME:
                    $paymentable = new BuyReadyProduct($this->paymentable);
                break;
            case \App\SaleReadyProduct::TABLE_NAME:
                    $paymentable = new SaleReadyProduct($this->paymentable);
                break;
            case \App\Order::TABLE_NAME:
                    $paymentable = new Order($this->paymentable);
                break;
            default: break;
        }

        return [
            'id'                => $this->id,
            'paymentable_type'  => $this->paymentable_type,
            'paymentable_id'    => $this->paymentable_id,
            'amount'            => floatval($this->amount),
            'paymentable'   => $paymentable
        ];
    }
}
