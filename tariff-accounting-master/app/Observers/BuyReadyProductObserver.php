<?php

namespace App\Observers;

use App\BuyReadyProduct;
use App\BuyReadyProductNotification;

class BuyReadyProductObserver
{
    public function creating(BuyReadyProduct $buyReadyProduct)
    {
        $buyReadyProduct->user_id = auth()->user()->id;
        if ($buyReadyProduct->paid_price == null){
            $buyReadyProduct->paid_price = 0;
        }
    }

    public function created(BuyReadyProduct $buyReadyProduct)
    {
        if( $buyReadyProduct->buy_notification_id )
        {
            if ( $notification = BuyReadyProductNotification::find($buyReadyProduct->buy_notification_id) ){
                $notification->update([
                    'status'    => BuyReadyProductNotification::WAITING
                ]);
            }
        }
    }

    public function updated(BuyReadyProduct $buyReadyProduct)
    {

    }

    public function deleted(BuyReadyProduct $buyReadyProduct)
    {
        $buyReadyProduct->buyProducts()->delete();
    }

    public function restored(BuyReadyProduct $buyReadyProduct)
    {
        //
    }

    public function forceDeleted(BuyReadyProduct $buyReadyProduct)
    {
        //
    }
}
