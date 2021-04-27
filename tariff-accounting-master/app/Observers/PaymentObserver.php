<?php

namespace App\Observers;

use App\ApplicationPart;
use App\Buy;
use App\BuyReadyProduct;
use App\Client;
use App\ContractClient;
use App\Order;
use App\Payment;
use App\Provider;
use App\SaleReadyProduct;

class PaymentObserver
{
    public function created(Payment $payment)
    {
        $paymentable = $payment->paymentable;
        switch ($payment->paymentable_type){
            case ApplicationPart::TABLE_NAME:
                    $paymentable->paid += $payment->amount;
                    break;
            default: break;
        }
        $paymentable->update();
    }

    public function updated(Payment $payment)
    {
        //
    }

    public function deleted(Payment $payment)
    {
        $paymentable = $payment->paymentable;
        switch ($payment->paymentable_type){
            case ApplicationPart::TABLE_NAME:
                $paymentable->paid -= $payment->amount;
                break;
            default: break;
        }
        $paymentable->update();

//        // dogovorga qaytib pulni qaytarish
//        $sourceable = $payment->sourceable;
//        switch ($payment->sourceable_type){
//            case ContractClient::TABLE_NAME:
//                $sourceable->sum += $payment->amount;
//                $sourceable->remainder += $payment->amount;
//                break;
//            default: break;
//        }
//        $sourceable->update();
    }

    public function restored(Payment $payment)
    {
        //
    }

    public function forceDeleted(Payment $payment)
    {
        //
    }
}
