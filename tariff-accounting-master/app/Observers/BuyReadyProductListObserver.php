<?php

namespace App\Observers;

use App\BuyReadyProductList;

class BuyReadyProductListObserver
{
    public function created(BuyReadyProductList $buyReadyProductList)
    {
        if ($buy = $buyReadyProductList->buy){
            $add = round(($buyReadyProductList->qty_weight * $buyReadyProductList->buy_price * $buyReadyProductList->rate),2);
            $buy->total_price += $add;
            $buy->update();
        };

    }

    public function updated(BuyReadyProductList $buyReadyProductList)
    {
        //
    }

    public function deleted(BuyReadyProductList $buyReadyProductList)
    {
        if ($buy = $buyReadyProductList->buy){
            $min = round(($buyReadyProductList->qty_weight * $buyReadyProductList->buy_price * $buyReadyProductList->rate),2);
            $buy->total_price -= $min;
            $buy->update();
        };

    }

    public function restored(BuyReadyProductList $buyReadyProductList)
    {
        //
    }

    public function forceDeleted(BuyReadyProductList $buyReadyProductList)
    {
        //
    }
}
