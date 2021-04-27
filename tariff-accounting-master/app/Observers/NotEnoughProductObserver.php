<?php

namespace App\Observers;

use App\AssemblyProduct;
use App\BuyReadyProductNotification;
use App\NotEnoughProduct;

class NotEnoughProductObserver
{

    public function created(NotEnoughProduct $notEnoughProduct)
    {
        if ($notify = BuyReadyProductNotification::find($notEnoughProduct->buy_ready_product_notification_id)){
            if($assemblyProduct = AssemblyProduct::where('assembly_id',$notify->buy_ready_product_notificationable_id)->where('product_id',$notEnoughProduct->product_id)->first()){
                $assemblyProduct->waiting_to_buy +=$notEnoughProduct->quantity;
                $assemblyProduct->update();
            }
        }
    }

    public function updated(NotEnoughProduct $notEnoughProduct)
    {
        //
    }

    public function deleted(NotEnoughProduct $notEnoughProduct)
    {
        //
    }

    public function restored(NotEnoughProduct $notEnoughProduct)
    {
        //
    }

    public function forceDeleted(NotEnoughProduct $notEnoughProduct)
    {
        //
    }
}
