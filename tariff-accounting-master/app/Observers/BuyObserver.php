<?php

namespace App\Observers;

use App\Buy;
use App\BuyNotification;
use Illuminate\Support\Facades\DB;

class BuyObserver
{
    public function creating(Buy $buy)
    {
        $buy->user_id = auth()->user()->id;
        if ($buy->paid_price == null){
            $buy->paid_price = 0;
        }
    }


    public function created(Buy $buy)
    {
        if( $buy->buy_notification_id )
        {
            if ( $notification = BuyNotification::find($buy->buy_notification_id) ){
                $notification->update([
                    'status'    => BuyNotification::WAITING
                ]);
            }
        }
    }

    public function updated(Buy $buy)
    {

    }

    /**
     * Handle the buy "deleted" event.
     *
     * @param  \App\Buy  $buy
     * @return void
     */
    public function deleted(Buy $buy)
    {
        //DB::table('buy_materials')->where('buy_id',$buy->id)->delete();
    }

    /**
     * Handle the buy "restored" event.
     *
     * @param  \App\Buy  $buy
     * @return void
     */
    public function restored(Buy $buy)
    {
        //
    }

    /**
     * Handle the buy "force deleted" event.
     *
     * @param  \App\Buy  $buy
     * @return void
     */
    public function forceDeleted(Buy $buy)
    {
        //
    }
}
